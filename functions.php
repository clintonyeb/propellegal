<?php
define ('SITE_ROOT', realpath(dirname(__FILE__)));
define("HOME", get_stylesheet_directory());
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require 'vendor/autoload.php';
require_once(SITE_ROOT . "/inc/global.php");

#require_once('inc/one-time-delete.php'); // Don't touch this!
#require_once('inc/one-time-init.php'); // Don't touch this!

use \Firebase\JWT\JWT;

$USER_PAYLOAD = array(
    'status' => false
);

if (array_key_exists('token', $_COOKIE)){
    validate_jwt($_COOKIE['token']);
}


if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Initialize Genesis
require_once get_template_directory() . '/lib/init.php';


function my_theme_enqueue_styles() {
    global $client_key;
    global $USER_PAYLOAD;
    wp_enqueue_script('util', get_bloginfo( 'stylesheet_directory' ) . '/assets/js/utils.js');
    wp_enqueue_script( 'script-js', get_bloginfo( 'stylesheet_directory' ) . '/assets/js/script.js', array('util', 'jquery'));
    wp_localize_script( 'script-js', '$wp_data',
                        array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'client_auth' => CLIENT_KEY, 'home' => HOME, 'authenticated' => $USER_PAYLOAD['status']));
}

add_action('wp_ajax_nopriv_register_form', 'register_form');
add_action('wp_ajax_register_form', 'register_form');
add_action('wp_ajax_nopriv_login_form', 'login_form');
add_action('wp_ajax_login_form', 'login_form');
add_action('wp_ajax_nopriv_get_doc', 'get_doc');
add_action('wp_ajax_get_doc', 'get_doc');
add_action('wp_ajax_nopriv_get_file', 'get_file');
add_action('wp_ajax_get_file', 'get_file');
add_action('wp_ajax_nopriv_upload_doc', 'upload_doc');
add_action('wp_ajax_upload_doc', 'upload_doc');
add_action('wp_ajax_nopriv_get_files', 'get_files');
add_action('wp_ajax_get_files', 'get_files');
add_action('wp_ajax_nopriv_generate_document', 'generate_document');
add_action('wp_ajax_generate_document', 'generate_document');
add_action('wp_ajax_get_pdf', 'get_pdf');
add_action('wp_ajax_nopriv_get_pdf', 'get_pdf');
add_action('wp_ajax_activate', 'activate');
add_action('wp_ajax_nopriv_activate', 'activate');
add_action('wp_ajax_recover_pass', 'recover_pass');
add_action('wp_ajax_nopriv_recover_pass', 'recover_pass');
add_action('wp_ajax_do_pass_recovery', 'do_pass_recovery');
add_action('wp_ajax_nopriv_do_pass_recovery', 'do_pass_recovery');
add_action('wp_ajax_ask_attorney', 'ask_attorney');
add_action('wp_ajax_nopriv_ask_attorney', 'ask_attorney');
add_action('wp_ajax_ask_business', 'ask_business');
add_action('wp_ajax_nopriv_ask_business', 'ask_business');
add_action('wp_ajax_req_mess', 'req_mess');
add_action('wp_ajax_nopriv_req_mess', 'req_mess');
add_action('wp_ajax_rev_mess', 'rev_mess');
add_action('wp_ajax_nopriv_rev_mess', 'rev_mess');
add_action('wp_ajax_reg_mess', 'reg_mess');
add_action('wp_ajax_nopriv_reg_mess', 'reg_mess');

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
add_filter('show_admin_bar', '__return_false');

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

// initial function calls

function wpdocs_set_html_mail_content_type() {
    return 'text/html';
}

// define the wp_mail_failed callback
function action_wp_mail_failed($wp_error)
{
    return error_log(print_r($wp_error, true));
}

// add the action
add_action('wp_mail_failed', 'action_wp_mail_failed', 10, 1);

function register_form(){
    global $wpdb;

    // check if request is from our client

    if (trim($_POST['client_key']) != CLIENT_KEY)
        return die(0);

    // verify captca

    $captcha = $_POST['captcha'];
    $captcha_url = "https://www.google.com/recaptcha/api/siteverify";
    $c_data = array(
        'secret' => CAPTCHA_SECRET_KEY,
        'response' => $captcha,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );

    $captcha_response = wp_remote_post( $captcha_url, array(
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $c_data,
        'cookies' => array()
    )
    );

    if ( is_wp_error( $captcha_response ) ) {
        $error_message = $captcha_response -> get_error_message();
        wp_send_json(array(
            'message' => $error_message,
            'status' => false
        ));

        return die(0);
    } else {
        $captcha_response = json_decode($captcha_response["body"]);
        $state = $captcha_response -> success;
        if (!$state){
            wp_send_json(array(
                'message' => 'Server could not validate sent data',
                'status' => false
            ));
            return die(0);
        }
    }

    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $password = trim($_POST['password']);

    if ($email == "" || $full_name == "" || $password == ""){
        wp_send_json(array(
            'message' => 'Server could not validate sent data',
            'status' => false
        ));
        return die(0);
    }

    if (strlen($email) < 1 || strlen($full_name) < 5 || strlen($password) < 6){
        wp_send_json(array(
            'message' => 'Server could not validate sent data',
            'status' => false
        ));
        return die(0);
    }

    if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)){
        wp_send_json(array(
            'message' => 'Server could not validate sent data',
            'status' => false
        ));
        return die(0);
    }

    // reached here, then client data has been validated

    $table_user = _USER_TABLE_;

    $email_exists = $wpdb->get_row("SELECT id,email FROM $table_user WHERE email = '$email'");

    if ($email_exists !== null){
        wp_send_json(array(
            'message' => "Account with email already exists, please login instead",
            'status' => false
        ));
        return die(0);
    }

    $p_options = [
        'cost' => 12,
    ];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT, $p_options);

    $result =  $wpdb->insert(
        $table_user,
        array(
            'date_created' => current_time( 'mysql' ),
            'email' => $email,
            'full_name' => $full_name,
            'password' => $hashed_password,
            'role_auth' => 1
        ),
        array(
            "%s",
            "%s",
            "%s",
            "%s",
            "%d"
        )
    );

    if ($result){

        $user_id = $wpdb -> insert_id;

        // send email

        //add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

        $otp = generateOTP($email);
        $admin = admin_url( 'admin-ajax.php' );
        $auth = "?action=activate&auth=$otp";
        $to = $email;
        $subject = 'Propellegal Account Confirmation';
        $body = '<html><head><title>Email Verification</title></head><body>';
        $body .= '<h2>Hi ' . $full_name . ' !</h2>';
        $body .= '<h4><a href="' . $admin . $auth . '">CLICK TO ACTICATE ACCOUNT</a></h4>';
        $body .= '</body></html>';

        $header = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-Type: text/html; charset=utf-8" . "\r\n";
        //$header .= "From: pdhutchinson@hutchinvestmentsllc.com" . "\r\n";

        $mail_res = wp_mail( $to, $subject, $body, $header );

        if ($mail_res) {
            error_log('Mail sent');
        } else {
            error_log('Mail failed');
        }

        //remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

        addActivity(_REGISTERED_ACCOUNT_, $user_id);

        wp_send_json(array(
            'message' => "Account Registration success. Please check your email for a confirmation email",
            'status' => true
        ));
    } else {
        wp_send_json(array(
            'message' => "Error during registration",
            'status' => false
        ));
    }

    die();
}

function activate(){
    global $wpdb;

    parse_str($_SERVER['QUERY_STRING']);

    $data = decode_jwt($auth, OTP_KEY);
    $email = $data -> data -> email;

    $table_user = _USER_TABLE_;

    if ($email) {
        $email_exists = $wpdb->get_row("
                    SELECT id, email, full_name
                    FROM $table_user
                    WHERE email = '$email'
");
        if ($email_exists){
            $up = $wpdb -> update(
                $table_user,
                array(
                    'activated' => 1
                ),
                array(
                    'id' => $email_exists -> id
                ),
                array(
                    '%d'
                ),
                array(
                    '%d'
                )
            );

            if ($up){
                echo '<h2>Account Activation Successfull</h2>';

                // send welcome message

                $to = $email_exists -> email;
                $subject = 'Congratulations';
                $body = '<html><head><title>Welcome</title></head><body>';
                $body .= '<h2>Hi ' . $email_exists -> full_name . ' !</h2>';
                $body .= '<p>Welcome Onboard</p>';
                $body .= '</body></html>';

                $header = "MIME-Version: 1.0" . "\r\n";
                $header .= "Content-Type: text/html; charset=utf-8" . "\r\n";

                $mail_res = wp_mail( $to, $subject, $body, $header );

                if ($mail_res) {
                    error_log('Mail sent');
                    addActivity(_ACTIVATED_ACCOUNT, $email_exists -> id);
                } else {
                    error_log('Mail failed');
                }

            } else {
                echo '<h2>Account already activated</h2>';
            }
        } else {
            echo '<h2>Activation Failed - Couldn\'t find Email</h2>';
        }
    } else {
        echo '<h2>Activation Failed</h2>';
    }
    die();
}

function login_form(){
    global $wpdb;

    // check if request is from our client

    if (trim($_POST['client_key']) != CLIENT_KEY)
        return die(0);

    $email = $_POST['email'];
    $password = $_POST['password'];

    // validate payload

    if ($email == "" || $password == ""){
        wp_send_json(array(
            'message' => 'Server could not validate sent data',
            'status' => false
        ));
        return die(0);
    }

    if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)){
        wp_send_json(array(
            'message' => 'Server could not validate sent data',
            'status' => false
        ));
        return die(0);
    }

    // user data validated

    $table_user = _USER_TABLE_;

    $results = $wpdb -> get_results("
                      SELECT email, password, id, full_name, role_auth, activated
                      FROM $table_user
                      WHERE email = '$email'
                      LIMIT 1;
");

    if (count($results) == 1){

        // valiate password match
        $res_data = $results[0];

        if($res_data -> activated != 1){
            wp_send_json(array(
                'message' => "Your Account is not activated, please check your mail to activate account",
                'status' => false,
            ));

            return die(0);
        }

        $hashed_password = $res_data -> password;

        if (password_verify($password, $hashed_password)) {
            $token = encryptData($res_data);

            setcookie('token', $token, time() + (86400 * 30 * 7), "/"); // 86400 = 7 days

            addActivity(_LOGGED_IN_, $res_data -> id);

            wp_send_json(array(
                'message' => "Login Success",
                'status' => true,
                'token' => $token
            ));
        } else {
            wp_send_json(array(
                'message' => "Username and Password Incorrect",
                'status' => false
            ));
        }

    } else {
        wp_send_json(array(
            'message' => "Username and Password Incorrect",
            'status' => false
        ));
    }
    die();
}

function recover_pass(){
    global $wpdb;
    global $phpmailer;

    // check if request is from our client

    if (trim($_POST['client_key']) != CLIENT_KEY)
        return die(0);


    $email = $_POST['email'];

    // validate payload

    if ($email == ""){
        wp_send_json(array(
            'message' => 'Server could not validate sent data',
            'status' => false
        ));
        return die(0);
    }

    if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)){
        wp_send_json(array(
            'message' => 'Server could not validate sent data',
            'status' => false
        ));
        return die(0);
    }

    // user data validated

    $table_user = _USER_TABLE_;

    $results = $wpdb -> get_results("SELECT email,id,full_name,role_auth,activated FROM $table_user WHERE email = '$email' LIMIT 1;");

    if (count($results) == 1){

        // valiate password match
        $res_data = $results[0];

        if($res_data -> activated != 1){
            wp_send_json(array(
                'message' => "Your Account is not activated, please check your mail to activate account",
                'status' => false,
            ));

            return die(0);
        }

        $otp = generateOTP($res_data -> email);
        $recover_page = get_home_url() . '/recover-account';
        $auth = "?auth=$otp";
        $to = $email;
        $subject = 'Propellegal Password Recovery';
        $body = '<html><head><title>Password Recovery</title></head><body>';
        $body .= '<h2>Hi ' . $res_data -> full_name . ' !</h2>';
        $body .= '<p>You requested to create a new password. If this was you, use the link below to create a new password.</p>';
        $body .= '</p>If you have mistakenly received this email, you can safely ignore it.</p>';
        $body .= '<h4><a href="' . $recover_page . $auth . '">CLICK TO RECOVER ACCOUNT PASSWORD</a></h4>';
        $body .= '</body></html>';

        $header = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-Type: text/html; charset=utf-8" . "\r\n";

        $mail_res = wp_mail( $to, $subject, $body, $header );

        if ($mail_res) {
            error_log('Mail sent');

        } else {
            return  wp_send_json(array(
                'message' => "Email failed to send " . $mail_res,
                'status' => false
            ));
        }

        wp_send_json(array(
            'message' => "Recovery link has been sent to your email account. Please check and follow the steps to recover your account",
            'status' => true
        ));
    } else {
        wp_send_json(array(
            'message' => "Email account does not exist",
            'status' => false
        ));
    }
    die();
}

function do_pass_recovery(){
    global $wpdb;

    // check if request is from our client

    if (trim($_POST['client_key']) != CLIENT_KEY)
        return die(0);


    $email = $_POST['email'];
    $password = $_POST['password'];

    // validate payload

    if ($email == "" || $password == ""){
        wp_send_json(array(
            'message' => 'Server could not validate sent data',
            'status' => false
        ));
        return die(0);
    }

    if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)){
        wp_send_json(array(
            'message' => 'Server could not validate sent data',
            'status' => false
        ));
        return die(0);
    }

    // user data validated

    $table_user = _USER_TABLE_;

    $results = $wpdb -> get_results("SELECT email,id,full_name,role_auth,activated FROM $table_user WHERE email = '$email' LIMIT 1;");

    if (count($results) == 1){
        $data = $results[0];

        $p_options = [
            'cost' => 12,
        ];

        $hashed_password = password_hash($password, PASSWORD_BCRYPT, $p_options);

        $up = $wpdb -> update(
            $table_user,
            array(
                'password' => $hashed_password
            ),
            array(
                'id' => ($data -> id)
            ),
            array(
                '%s'
            ),
            array(
                '%d'
            )
        );

        if ($up === false){
            wp_send_json(array(
                'message' => 'Error changing password db',
                'status' => false
            ));

            return die(0);
        } else {
            addActivity(_RECOVER_PASSWORD_, $data -> id);
            wp_send_json(array(
                'message' => 'Password succesfully changed, please login to your account using the new password',
                'status' => true
            ));
        }
    } else {
        wp_send_json(array(
            'message' => 'Error changing password',
            'status' => false
        ));
        return die(0);
    }

    die();
}

function generateOTP($email){
    $token_id = base64_encode(rand_sha1(32));
    $issued_at = time();
    $not_before = $issued_at + 10;
    $expire = $not_before + 86400; # 1 day

    $data = [
        'iat' => $issued_at,
        'jti' => $token_id,
        #'iss' => $server_name,
        'nbf' => $not_before,
        'exp' => $expire,
        'data' => [
            'email' => $email,
        ]
    ];

    $otp = JWT::encode(
        $data,
        OTP_KEY,
        'HS256'
    );

    return $otp;
}


function encryptData($payload){
    if($payload){
        $token_id = base64_encode(rand_sha1(32));
        $issued_at = time();
        $not_before = $issued_at + 10;
        $expire = $not_before + 86400 * 7; # 7 days

        $data = [
            'iat' => $issued_at,
            'jti' => $token_id,
            #'iss' => $server_name,
            'nbf' => $not_before,
            'exp' => $expire,
            'data' => [
                'user_id' => $payload -> id,
                'user_name' => $payload -> email,
                'role' => role_id_to_string($payload -> role_auth),
                'full_name' => $payload -> full_name
            ]
        ];

        //$secret_key = base64_encode($secret_key);
        $jwt = JWT::encode(
            $data,
            SECRET_KEY,
            'HS256'
        );

        return $jwt;
    }

    return null;
}

function rand_sha1($length) {
    $max = ceil($length / 40);
    $random = '';
    for ($i = 0; $i < $max; $i ++) {
        $random .= sha1(microtime(true).mt_rand(10000,90000));
    }
    return substr($random, 0, $length);
}


function decode_jwt($jwt, $key, $h = false){
    JWT::$leeway = 60;
    $decoded = '';

    try{
        $decoded = JWT::decode($jwt, $key, array('HS256'));
    } catch(\Exception $e){
        if (!$h){
            setcookie("token", "", 1, '/');
        }
        return NULL;
    }

    return $decoded;
}

function validate_jwt($jwt){
    global $USER_PAYLOAD;

    if ($jwt){
        $decoded = decode_jwt($jwt, SECRET_KEY);
        if ($decoded && $decoded -> exp > time()){
            $USER_PAYLOAD =  array(
                'status' => true,
                'data' => $decoded -> data
            );
        }
    }
}

function addActivity($type_name, $user_id){
    global $wpdb;

    $table_activities = _ACTIVITY_TABLE_;
    $date = date('Y-m-d H:i:s');

    $sql_insert = $wpdb -> insert(
        $table_activities,
        array(
            "date_created" => $date,
            "type_name" => $type_name,
            "user_id" => $user_id
        ),
        array(
            "%s",
            "%s",
            "%d"
        )
    );

    return $wpdb -> insert_id;
}

function get_files(){
    $category = $_POST['category'];
    $state = $_POST['state'];

    $dir = HOME . "/assets/docs/$state/$category";

    if (is_dir($dir)){
        $files = array_diff(scandir($dir, 1), array('.', '..'));

        wp_send_json(array(
            'message' => 'Success',
            'status' => true,
            'data' => json_encode($files)
        ));
    } else {
        echo "No such directory";
    }

    die();
}

$phpWord = new \PhpOffice\PhpWord\PhpWord();


function generate_document(){
    global $phpWord;
    global $wpdb;
    global $USER_PAYLOAD;

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $doc_state = $_POST['doc_state'];
    $state = $_POST['state'];
    $category = $_POST['category'];
    $doc_name = $_POST['docName'];

    $m = new Mustache_Engine;
    $user = $USER_PAYLOAD['data'];

    $path = get_stylesheet_directory() . "/assets/docs/$state/$category";
    $template = $path . "/$doc_name" . '.tpl';

    $tmp_content =  file_get_contents($template);

    $result = $m -> render($tmp_content, array(
        'user_name' => $firstname . ' ' . $lastname,
        'category' => $category,
        'address' => $address,
        'city' => $city,
        'country' => $country,
        'state' => $state
    ));

    $section = $phpWord -> addSection();

    $section -> addText($result);

    $out_path = get_stylesheet_directory() . "/assets/generated_documents/";
    $out_file = uniqid('output-', true);

    /* $gen_file_docx = $out_path . $out_file . '.docx';
     * $gen_file_odt = $out_path . $out_file . '.odt';*/
    $gen_file_html = $out_path . $out_file . '.html';
    $gen_file_pdf = $out_path . $out_file . '.pdf';
    $gen_file_jpg = $out_path . $out_file . '.jpg';

    /* $docWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
     * $docWriter->save($gen_file_docx);*/

    /* $odtWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
     * $odtWriter-> save($gen_file_odt);*/

    $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
    $htmlWriter->save($gen_file_html);

    file_put_contents($gen_file_pdf, get_pdf($gen_file_html));

    $pdf = new Spatie\PdfToImage\Pdf($gen_file_pdf);
    $pdf->saveImage($gen_file_jpg);

    $table_doc = _DOC_TABLE_;

    $act_id = addActivity(_CREATE_DOCUMENT_, $user -> user_id);

    $res = $wpdb -> insert($table_doc,
                           array(
                               "act_id" => $act_id,
                               "category" => $category,
                               "state" => $state,
                               "request_type" => "self",
                               "file_name" => $out_file
                           ), array(
                               "%d", "%s", "%s", "%s", "%s"
                           )
    );

    if ($res){
        wp_send_json(array(
            'message' => 'Success',
            'status' => true,
            'data' => $out_file
        ));
    } else {
        wp_send_json(array(
            'message' => 'Error creating document',
            'status' => false,
        ));
    }

    die();
}

use Dompdf\Dompdf;

function get_pdf($html){
    $tmp_content =  file_get_contents($html);
    $dompdf = new Dompdf();
    $dompdf->loadHtml($tmp_content);

    $dompdf->render();
    return $dompdf->output();
}


function upload_doc(){
    global $USER_PAYLOAD;
    global $wpdb;

    $files = $_FILES['file'];
    $name = $_POST['name'];
    $content = $_POST['content'];
    $user = $USER_PAYLOAD['data'];
    $table_doc_reviews = _DOC_REVIEW_TABLE_;
    $table_doc_files = _DOC_FILES_;
    $date = $date = date('Y-m-d H:i:s');
    $save_files = array();

    // Authenticate request
    if (trim($_POST['client_key']) != CLIENT_KEY)
        return die(0);

    $total = count($files['name']);

    for($i = 0; $i < $total; $i++){
        $target_file = _saveFile($files, $i);

        if ($target_file){
            array_push($save_files, $target_file);
        } else {
            sendError("Error uploading files");
        }
    }

    $act_id = addActivity(_REVIEW_DOCUMENT_, $user -> user_id);

    $res = $wpdb -> insert($table_doc_reviews,
                           array(
                               "act_id" => $act_id,
                               "mess" => $content,
                               "status" => _RECEIVED_,
                               "last_updated" => $date,
                               "viewed" => 0,
                               "doc_user_name" => $name,
                           ), array(
                               "%d", "%s", "%s", "%s", "%d", "%s"
                           ));

    if ($res){
        $doc_id = $wpdb -> insert_id;

        // add files
        foreach ($save_files as $f){
            $wpdb -> insert($table_doc_files,
                            array(
                                "doc_id" => $doc_id,
                                "path" => $f
                            ), array(
                                "%d", "%s"
                            ));
        }

        wp_send_json(array(
            'message' => 'Success',
            'status' => true,
        ));
    }

    wp_send_json(array(
        'message' => 'Failed',
        'status' => false,
    ));

    die();
}


function _saveFile($files, $i, $crush = 0){
    $target_dir =  "assets/uploads/";
    $target_file = $target_dir . basename($files['name'][$i]);

    if ($crush != 0){
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $imageName = pathinfo($target_file, PATHINFO_FILENAME);

        $target_file = $target_dir . $imageName . '-' . $crush . '.' . $imageFileType;
    }

    if (file_exists(SITE_ROOT . '/' . $target_file)) {
        return _saveFile($files, $i, ++$crush);
    }

    $res = move_uploaded_file($files["tmp_name"][$i], SITE_ROOT . '/' .  $target_file);

    if ($res)
        return pathinfo($target_file, PATHINFO_BASENAME);

    return false;
}

function role_id_to_string($id){
    global $USER_ROLE;
    global $ADMIN_ROLE;
    global $LAWYER_ROLE;

    switch($id){
        case 1:
            return $USER_ROLE;
        case 2:
            return $LAWYER_ROLE;
        case 3:
            return $ADMIN_ROLE;
        default:
            return '';
    }
}

add_action( 'phpmailer_init', 'configure_smtp' );

function configure_smtp( PHPMailer $phpmailer ){
    $phpmailer->isSMTP(); //switch to smtp
    $phpmailer->Host = "relay-hosting.secureserver.net";
    //$phpmailer->SMTPSecure = 'tls';
    //$phpmailer->SMTPAuth = true;
    $phpmailer->Port = 25;
    $phpmailer->Username = 'admin@propellegal.com';
    $phpmailer->Password = 'pass@123';
    $phpmailer->From = 'admin@propellegal.com';
    $phpmailer->FromName = 'Propellegal';
    //$phpmailer->SMTPDebug = 2;
}

/* if(!$phpmailer ->Send()) {
 *     echo 'Message could not be sent.';
 *     echo 'Mailer Error: ' . $phpmailer->ErrorInfo;
 *     exit;
 * }*/


function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}


function ask_attorney(){
    global $USER_PAYLOAD;
    global $wpdb;
    $content = $_POST['content'];

    if (trim($_POST['client_key']) != CLIENT_KEY)
        return die(0);

    error_log(print_r($USER_PAYLOAD, true));
    // validate cookie
    if(!$USER_PAYLOAD["status"]) return redirect("/login");
    $user = $USER_PAYLOAD["data"];
    $date = date('Y-m-d H:i:s');
    $act_id = addActivity(_ASK_ATTORNEY_, $user -> user_id);

    $table_requests = _REQUEST_TABLE_;
    $res = $wpdb -> insert($table_requests,
                           array(
                               "act_id" => $act_id,
                               "mess" => $content,
                               "status" => _RECEIVED_,
                               "last_updated" => $date,
                               "viewed" => 0
                           ), array(
                               "%d",
                               "%s",
                               "%s",
                               "%s",
                               "%d"
                           )
    );

    if ($res){

        $res = addRequestMessage($user-> user_id, $wpdb -> insert_id, $content);
        if ($res){
            wp_send_json(array(
                'message' => 'Success',
                'status' => true));
        } else{
            wp_send_json(array(
                'message' => 'Error adding request',
                'status' => false));
        }

    } else {
        wp_send_json(array(
            'message' => 'Error adding request',
            'status' => false));
    }

    die();
}

function addRequestMessage($user_id, $req_id, $content){
    global $wpdb;

    $date = date('Y-m-d H:i:s');

    $table_req_mess = _REQUEST_MESS_;

    $res = $wpdb -> insert(
        $table_req_mess,
        array(
            "req_id" => $req_id,
            "user_id" => $user_id,
            "content" => $content,
            "date_created" => $date
        ),
        array(
            "%d",
            "%d",
            "%s",
            "%s"
        )
    );

    return $res;
}


function req_mess(){
    $content = $_POST['content'];
    $req_id =  $_POST['req_id'];
    global $USER_PAYLOAD;
    $user = $USER_PAYLOAD['data'];

    if (addRequestMessage($user -> user_id, $req_id, $content)) {
        wp_send_json(array(
            'message' => 'Success',
            'status' => true));
    } else {
        wp_send_json(array(
            'message' => 'error adding request',
            'status' => false));
    }

    die();
}

function addReviewMessage($d, $table_name){
    global $wpdb;

    $res = $wpdb -> insert(
        $table_name,
        $d,
        array(
            "%d",
            "%d",
            "%s",
            "%s"
        )
    );

    return $res;
}

function rev_mess(){
    global $USER_PAYLOAD;

    $content = $_POST['content'];
    $req_id =  $_POST['req_id'];
    $user = $USER_PAYLOAD['data'];
    $user_id = $user -> user_id;
    $date = date('Y-m-d H:i:s');
    $table_rev_mess = _DOC_REVIEW_MESS_;

    $d = array(
        "doc_id" => $req_id,
        "user_id" => $user_id,
        "content" => $content,
        "date_created" => $date
    );

    if (addReviewMessage($d, $table_rev_mess)) {
        wp_send_json(array(
            'message' => 'Success',
            'status' => true));
    } else {
        wp_send_json(array(
            'message' => 'error adding review',
            'status' => false));
    }

    die();
}

function reg_mess(){
    global $USER_PAYLOAD;

    $content = $_POST['content'];
    $req_id =  $_POST['req_id'];
    $user = $USER_PAYLOAD['data'];
    $user_id = $user -> user_id;
    $date = date('Y-m-d H:i:s');

    $table_reg_mess = _BUS_MESS_TABLE_;
    $d = array(
        "reg_id" => $req_id,
        "user_id" => $user_id,
        "content" => $content,
        "date_created" => $date
    );

    if (addReviewMessage($d, $table_reg_mess)) {
        wp_send_json(array(
            'message' => 'Success',
            'status' => true));
    } else {
        wp_send_json(array(
            'message' => 'error adding review',
            'status' => false));
    }

    die();
}

function ask_business(){
    global $wpdb;
    global $USER_PAYLOAD;

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $busType = $_POST['busType'];
    $comName = $_POST['comName'];
    $comDesc = $_POST['comDesc'];
    $mess = $_POST['mess'];

    $table_registrations = _BUS_REG_TABLE_;
    $user = $USER_PAYLOAD['data'];
    $date = date('Y-m-d H:i:s');

    $act_id = addActivity(_REGISTER_BUSINESS_, $user -> user_id);

    $res = $wpdb -> insert($table_registrations,
                           array(
                               "act_id" => $act_id,
                               "mess" => $mess,
                               "status" => _RECEIVED_,
                               "last_updated" => $date,
                               "viewed" => 0,
                               "bus_fname" => $firstname,
                               "bus_lname" => $lastname,
                               "bus_phone" => $phone,
                               "bus_city" => $city,
                               "bus_state" => $state,
                               "bus_zipcode" => $zip,
                               "bus_address" => $address,
                               "bus_type" => $busType,
                               "com_name" => $comName,
                               "com_desc" => $comDesc
                           ), array(
                               "%d", "%s", "%s", "%s", "%d", "%s", "%s",
                               "%s", "%s", "%s", "%s", "%s", "%s", "%s"
                           ));

    if ($res){
        wp_send_json(array(
            'message' => 'Success',
            'status' => true));
    } else {
        wp_send_json(array(
            'message' => 'Failed',
            'status' => false));
    }

    die();
}

function getActivities($limit = 10){
    global $wpdb;
    global $USER_PAYLOAD;

    $user = $USER_PAYLOAD['data'];
    $table_activities = _ACTIVITY_TABLE_;
    $user_id = $user -> user_id;

    $query = "SELECT id, date_created, type_name
             FROM $table_activities
             WHERE user_id = $user_id
             ORDER BY date_created DESC
             LIMIT $limit;";

    $results = $wpdb -> get_results($query, OBJECT);
    return $results;
}

function getRequests($limit = 10){
    global $wpdb;

    $table_requests = _REQUEST_TABLE_;
    $table_activities = _ACTIVITY_TABLE_;

    $query = "SELECT $table_requests.id, date_created, type_name, status
             FROM $table_requests
             INNER JOIN $table_activities
             ON $table_requests.act_id = $table_activities.id
             ORDER BY date_created DESC
             LIMIT $limit;";

    $results = $wpdb -> get_results($query, OBJECT);
    return $results;
}

$PAGE = 0;
$DATA_COUNT = 0;

function getAllRequests($limit = 20, $page = 1, $q = ""){
    global $wpdb;
    global $PAGE;
    global $USER_PAYLOAD;
    global $DATA_COUNT;

    $table_requests = _REQUEST_TABLE_;
    $table_activities = _ACTIVITY_TABLE_;
    $offset = $limit * ($page - 1);
    $PAGE = $page;
    $user = $USER_PAYLOAD['data'];
    $user_id = $user -> user_id;

    if ($q){

        $query = "SELECT COUNT($table_requests.id)
             FROM $table_requests
             INNER JOIN $table_activities
             ON $table_requests.act_id = $table_activities.id
             WHERE user_id = $user_id
             AND mess LIKE '%s';";


        $query = $wpdb -> prepare(
            $query, array(
                "%$q%"
            )
        );

        $DATA_COUNT = $wpdb -> get_var($query);


        $query = "SELECT $table_requests.id, last_updated, type_name, mess, viewed, status
             FROM $table_requests
             INNER JOIN $table_activities
             ON $table_requests.act_id = $table_activities.id
             WHERE user_id = $user_id
             AND mess LIKE '%s'
             ORDER BY date_created DESC
             LIMIT $limit";

        $query = $wpdb -> prepare(
            $query, array(
                "%$q%"
            )
        );

        $results = $wpdb -> get_results($query, OBJECT);

        return $results;
    }

    $query = "SELECT COUNT($table_requests.id)
             FROM $table_requests
             INNER JOIN $table_activities
             ON $table_requests.act_id = $table_activities.id
             WHERE user_id = $user_id;";

    $DATA_COUNT = $wpdb -> get_var($query);

    $query = "SELECT $table_requests.id, last_updated, type_name, mess, viewed, status
             FROM $table_requests
             INNER JOIN $table_activities
             ON $table_requests.act_id = $table_activities.id
             WHERE user_id = $user_id
             ORDER BY date_created DESC
             LIMIT $limit;";

    $results = $wpdb -> get_results($query, OBJECT);
    return $results;
}

function getActivityTemplate($act){

    if (!$act){
        return (
            '
                <article class="media">
                <figure class="media-left">
                <p class="media-icon">
                <span class="icon">
                <i class="fa fa-paper-plane-o"></i>
                </span>
                </p>
                </figure>
                <div class="media-content">
                <div class="content">
                <p>
                &middot;
                </p>
                </div>
                </div>
                </article>
                '
        );

    }
    $date = time_elapsed_string($act -> date_created);
    $content = "";

    switch($act -> type_name){
        case _CREATE_DOCUMENT_:
            $content = 'You created a new <strong>document</strong>';
            break;
        case _LOGGED_IN_:
            $content = 'You <strong>logged into</strong> your account';
            break;
        case _UPLOAD_DOCUMENT_:
            $content = 'You <strong>uploaded</strong> a new document';
            break;
        case _REGISTERED_ACCOUNT_:
            $content = 'You created a new <strong>account</strong>';
            break;
        case _ACTIVATED_ACCOUNT_:
            $content = 'You <strong>activated</strong> your account';
            break;
        case _RECOVER_PASSWORD_:
            $content = 'You recovered your account <strong>password</strong>';
            break;
        case _ASK_ATTORNEY_:
            $content = 'You sent a request to an <strong>antorney</strong>';
            break;
        case _REVIEW_DOCUMENT_:
            $content = 'You sent a document for <strong>review</strong>';
            break;
        case _REGISTER_BUSINESS_:
            $content = 'You requested to <strong>register</strong> a business';
            break;
    case _ACTIVATED_ACCOUNT_:
            $content = 'You have successfully <strong>activated</strong> your account';
            break;
        default:
            $date = "";
            $content = "";
            break;
    }

    return (
        '
                <article class="media">
                <figure class="media-left">
                <p class="media-icon">
                <span class="icon">
                <i class="fa fa-paper-plane-o"></i>
                </span>
                </p>
                </figure>
                <div class="media-content">
                <div class="content">
                <p>' .
        $content . ' &middot; <small> ' . $date . '</small>
                </p>
                </div>
                </div>
                </article>
                '
    );

}

function getAllRequestsTemplate($req){

    $status = $req -> status;
    $viewed = $req -> viewed;
    $mess = $req -> mess;
    $last_updated = time_elapsed_string($req -> last_updated);
    $req_id = $req -> id;
    $status_color = get_color($status);

    return (
        "

         <td style=\"width: 5%\">
           <p class=\"media-icon\">
             <span class=\"icon $status_color\">
               <i class=\"fa fa-circle\"></i>
             </span>
          </p>
         </td>

        <td  style=\"width: 85%\">
           <p>$mess</p>
       </td>
       <td  style=\"width: 10%; padding-top: 1.5rem\">
         <small class=\"has-text-centered\">$last_updated</small>
       </td>
        "
    );
}

function get_color($status){
    switch($status){
        case _RECEIVED_:
            return "has-text-warning";
        case _PROCESSING_:
            return "has-text-info";
            break;
        case _COMPLETED_:
            return "has-text-success";
            break;

    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'yr',
        'm' => 'mth',
        'w' => 'wk',
        'd' => 'day',
        'h' => 'hr',
        'i' => 'min',
        's' => 'sec',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function getActivityCount($act_name){
    global $wpdb;
    global $USER_PAYLOAD;

    $user = $USER_PAYLOAD['data'];
    $table_activities = _ACTIVITY_TABLE_;
    $user_id = $user -> user_id;

    $query = "
           SELECT COUNT(id)
           FROM $table_activities
           WHERE type_name = '$act_name'
           AND user_id = '$user_id'
     ";

    $results = $wpdb -> get_var($query);
    return $results;
}

function getRequestMessages($req_id){
    global $wpdb;

    $table_requests = _REQUEST_TABLE_;
    $table_users = _USER_TABLE_;
    $table_mess = _REQUEST_MESS_;

    $query = "SELECT content, $table_mess.date_created, full_name
             FROM $table_mess
             INNER JOIN $table_users
             ON $table_mess.user_id = $table_users.id
             WHERE req_id= $req_id;";

    $results = $wpdb -> get_results($query, OBJECT);
    return $results;
}

function getRequestMessagesTemplate($mess){

    $full_name = $mess -> full_name;
    $date = time_elapsed_string($mess -> date_created);
    $content = $mess -> content;

    return(
        "
          <article class=\"media\" style=\"max-width: 85%\">
  <figure class=\"media-left\">
    <p class=\"image is-64x64\">
      <img src=\"https://bulma.io/images/placeholders/128x128.png\">
        </p>
        </figure>
        <div class=\"media-content\">
        <div class=\"content\">
        <p>
        <strong>$full_name</strong> <small>$date</small>
        <br>
           $content
        </p>
        </div>
        </div>
        </article>
        "
    );
}


function sendError($mess = "Error"){
    wp_send_json(array(
        'message' => $mess,
        'status' => false
    ));
}

function sendResponse($mess = "Success", $data = array()){
    wp_send_json(array(
        'message' => $mess,
        'status' => true,
        'data'=> $data
    ));
}


function getAllDocReviews($limit = 20, $page = 1, $q = ""){
    global $wpdb;
    global $PAGE;
    global $USER_PAYLOAD;
    global $DATA_COUNT;

    $table_doc_reviews = _DOC_REVIEW_TABLE_;
    $table_activities = _ACTIVITY_TABLE_;
    $offset = $limit * ($page - 1);
    $PAGE = $page;
    $user = $USER_PAYLOAD['data'];
    $user_id = $user -> user_id;

    if ($q){

        $query = "SELECT COUNT($table_doc_reviews.id)
             FROM $table_doc_reviews
             INNER JOIN $table_activities
             ON $table_doc_reviews.act_id = $table_activities.id
             WHERE user_id = $user_id
             AND mess LIKE '%s';";


        $query = $wpdb -> prepare(
            $query, array(
                "%$q%"
            )
        );

        $DATA_COUNT = $wpdb -> get_var($query);


        $query = "SELECT $table_doc_reviews.id, last_updated, type_name, mess, viewed, status
             FROM $table_doc_reviews
             INNER JOIN $table_activities
             ON $table_doc_reviews.act_id = $table_activities.id
             WHERE user_id = $user_id
             AND mess LIKE '%s'
             ORDER BY last_updated DESC
             LIMIT $limit";

        $query = $wpdb -> prepare(
            $query, array(
                "%$q%"
            )
        );

        $results = $wpdb -> get_results($query, OBJECT);

        return $results;
    }

    $query = "SELECT COUNT($table_doc_reviews.id)
             FROM $table_doc_reviews
             INNER JOIN $table_activities
             ON $table_doc_reviews.act_id = $table_activities.id
             WHERE user_id = $user_id;";

    $DATA_COUNT = $wpdb -> get_var($query);

    $query = "SELECT $table_doc_reviews.id, last_updated, type_name, mess, viewed, status
             FROM $table_doc_reviews
             INNER JOIN $table_activities
             ON $table_doc_reviews.act_id = $table_activities.id
             WHERE user_id = $user_id
             ORDER BY last_updated DESC
             LIMIT $limit;";

    $results = $wpdb -> get_results($query, OBJECT);
    return $results;
}


function getAllDocReviewsTemplate($req){

    $status = $req -> status;
    $viewed = $req -> viewed;
    $mess = $req -> mess;
    $last_updated = time_elapsed_string($req -> last_updated);
    $req_id = $req -> id;
    $status_color = get_color($status);

    return (
        "
         <td style=\"width: 5%\">
           <p class=\"media-icon\">
             <span class=\"icon $status_color\">
               <i class=\"fa fa-circle\"></i>
             </span>
          </p>
         </td>

        <td  style=\"width: 85%\">
           <p>$mess</p>
       </td>
       <td  style=\"width: 10%; padding-top: 1.5rem\">
         <small class=\"has-text-centered\">$last_updated</small>
       </td>
        "
    );
}

function getDocRevDetails($req_id){
    global $wpdb;

    $table_docs = _DOC_REVIEW_TABLE_;
    $table_users = _USER_TABLE_;
    $table_mess = _DOC_REVIEW_MESS_;
    $table_files = _DOC_FILES_;
    $table_activities = _ACTIVITY_TABLE_;

    $query = "SELECT $table_docs.id,  mess, $table_activities.date_created, full_name, doc_user_name, status
             FROM $table_docs
             INNER JOIN $table_activities
             ON $table_docs.act_id = $table_activities.id
             INNER JOIN $table_users
             ON $table_activities.user_id = $table_users.id
             WHERE $table_docs.id = $req_id
             LIMIT 1;";


    $results = $wpdb -> get_results($query, OBJECT);
    return $results[0];
}



function getDocRevDetailsTemplate($req, $fn){
    $status = $req -> status;
    $mess = $req -> mess;
    $date = time_elapsed_string($req -> date_created);
    $doc_id = $req -> id;
    $status_color = get_color($status);
    $full_name = $req -> full_name;
    $doc_user_name = $req -> doc_user_name;

    $files = "<strong>$fn</strong> file";
    if ($fn > 1)
        $files .= "s";
    $files .= " uploaded";

    return(
        "
          <article class=\"media\" style=\"max-width: 85%\">
  <figure class=\"media-left\">
    <p class=\"image is-64x64\">
      <img src=\"https://bulma.io/images/placeholders/128x128.png\">
        </p>
        </figure>
        <div class=\"media-content\">
        <div class=\"content\">
        <p>
        <strong>$full_name</strong> &middot; <small>$date</small>
        <br>
           $mess
        </p>
        <p>
          <strong>Full name: </strong> $doc_user_name
        </p>
        <p>
          <span class=\"icon is-small has-text-darker-yellow\">
             <i class=\"fa fa-file\"></i>
          </span>
          <span> $files </span>
         </p>
        </div>
        </div>
        </article>
        "
    );
}


function getFileCount($req_id){
    global $wpdb;

    $table_files = _DOC_FILES_;

    $query = "SELECT COUNT(id)
             FROM $table_files
             WHERE $table_files.doc_id = $req_id;";


    $results = $wpdb -> get_var($query);
    return $results;
}

function getMessages($id, $type_id, $table_name){
    global $wpdb;

    $table_users = _USER_TABLE_;

    $query = "SELECT content, $table_name.date_created, full_name
             FROM $table_name
             INNER JOIN $table_users
             ON $table_name.user_id = $table_users.id
             WHERE $type_id = $id;";


    $results = $wpdb -> get_results($query, OBJECT);
    return $results;
}

function getReviewMessages($doc_id){
    $table_mess = _DOC_REVIEW_MESS_;
    return getMessages($doc_id, 'doc_id', $table_mess);
}

function getRevMessTemplate($rev){
    $full_name = $rev -> full_name;
    $date = time_elapsed_string($rev -> date_created);
    $content = $rev -> content;

    return(
        "
          <article class=\"media\" style=\"max-width: 85%\">
  <figure class=\"media-left\">
    <p class=\"image is-64x64\">
      <img src=\"https://bulma.io/images/placeholders/128x128.png\">
        </p>
        </figure>
        <div class=\"media-content\">
        <div class=\"content\">
        <p>
        <strong>$full_name</strong> <small>$date</small>
        <br>
           $content
        </p>
        </div>
        </div>
        </article>
        "
    );
}

function getAllBusRegs($limit = 20, $page = 1, $q = ""){
    global $wpdb;
    global $PAGE;
    global $USER_PAYLOAD;
    global $DATA_COUNT;

    $table_regs = _BUS_REG_TABLE_;
    $table_activities = _ACTIVITY_TABLE_;
    $offset = $limit * ($page - 1);
    $PAGE = $page;
    $user = $USER_PAYLOAD['data'];
    $user_id = $user -> user_id;

    if ($q){

        $query = "SELECT COUNT($table_regs.id)
             FROM $table_regs
             INNER JOIN $table_activities
             ON $table_regs.act_id = $table_activities.id
             WHERE user_id = $user_id
             AND mess LIKE '%s';";


        $query = $wpdb -> prepare(
            $query, array(
                "%$q%"
            )
        );

        $DATA_COUNT = $wpdb -> get_var($query);


        $query = "SELECT $table_regs.id, last_updated, type_name, mess, viewed, status
             FROM $table_regs
             INNER JOIN $table_activities
             ON $table_regs.act_id = $table_activities.id
             WHERE user_id = $user_id
             AND mess LIKE '%s'
             ORDER BY last_updated DESC
             LIMIT $limit";

        $query = $wpdb -> prepare(
            $query, array(
                "%$q%"
            )
        );

        $results = $wpdb -> get_results($query, OBJECT);

        return $results;
    }

    $query = "SELECT COUNT($table_regs.id)
             FROM $table_regs
             INNER JOIN $table_activities
             ON $table_regs.act_id = $table_activities.id
             WHERE user_id = $user_id;";

    $DATA_COUNT = $wpdb -> get_var($query);

    $query = "SELECT $table_regs.id, last_updated, type_name, mess, viewed, status
             FROM $table_regs
             INNER JOIN $table_activities
             ON $table_regs.act_id = $table_activities.id
             WHERE user_id = $user_id
             ORDER BY last_updated DESC
             LIMIT $limit;";

    $results = $wpdb -> get_results($query, OBJECT);
    return $results;
}

function getRegDetails($req_id){
    global $wpdb;

    $table_regs = _BUS_REG_TABLE_;
    $table_users = _USER_TABLE_;
    $table_mess = _BUS_MESS_TABLE_;
    $table_activities = _ACTIVITY_TABLE_;

    $query = "SELECT $table_regs.id, mess, $table_activities.date_created, full_name, status, bus_fname, bus_lname, bus_phone, bus_city, bus_state, bus_zipcode, bus_address, bus_type, com_name, com_desc
             FROM $table_regs
             INNER JOIN $table_activities
             ON $table_regs.act_id = $table_activities.id
             INNER JOIN $table_users
             ON $table_activities.user_id = $table_users.id
             WHERE $table_regs.id = $req_id
             LIMIT 1;";


    $results = $wpdb -> get_results($query, OBJECT);
    return $results[0];
}

function getRegDetailsTemp($reg) {
    $full_name = $reg -> full_name;
    $date = time_elapsed_string($reg -> date_created);
    $mess = $reg-> mess;
    $firtname = $reg -> bus_fname;
    $lastname = $reg -> bus_lname;
    $bus_phone = $reg -> bus_phone;
    $bus_city = $reg -> bus_city;
    $bus_state = $reg -> bus_state;
    $bus_zipcode = $reg -> bus_zipcode;
    $bus_address = $reg -> bus_address;
    $bus_type = $reg -> bus_type;
    $com_name = $reg -> com_name;
    $com_desc = $reg -> com_desc;

    return(
        "
          <article class=\"media\" style=\"max-width: 85%\">
  <figure class=\"media-left\">
    <p class=\"image is-64x64\">
      <img src=\"https://bulma.io/images/placeholders/128x128.png\">
        </p>
        </figure>
        <div class=\"media-content\">
        <div class=\"content\">
        <p>
        <strong>$full_name</strong> <small>$date</small>
        <br>
           $mess
        </p>
        </div>
        </div>
        </article>

        <div class=\"has-margin-top-2\">
        <div class=\"columns\">
           <div class=\"column\">
              <strong>FirstName: </strong> $firtname
           </div>
           <div class=\"column\">
               <strong>LastName</strong> $lastname
           </div>
        </div>

        <div class=\"columns\">
           <div class=\"column\">
              <strong>Phone Number: </strong> $bus_phone
           </div>
           <div class=\"column\">
               <strong>Business City: </strong> $bus_city
           </div>
        </div>

        <div class=\"columns\">
           <div class=\"column\">
              <strong>Business State: </strong> $bus_state
           </div>
           <div class=\"column\">
               <strong>Business ZIPCode: </strong> $bus_zipcode
           </div>
        </div>

        <div class=\"columns\">
           <div class=\"column\">
              <strong>Business Address: </strong> $bus_address
           </div>
           <div class=\"column\">
               <strong>Business Type: </strong> $bus_type
           </div>
        </div>

         <div class=\"columns\">
           <div class=\"column\">
              <strong>Company Name: </strong> $com_name
           </div>
           <div class=\"column\">
               <strong>Company Description: </strong> $com_desc
           </div>
        </div>
        </div>
        <hr />
        "
    );
}

function getRegMessages($reg_id){
    $table_mess = _BUS_MESS_TABLE_;
    return getMessages($reg_id, 'reg_id', $table_mess);
}


function getAllCreatDocuments($limit = 20, $page = 1, $q = ""){
    global $wpdb;
    global $PAGE;
    global $USER_PAYLOAD;
    global $DATA_COUNT;

    $table_doc = _DOC_TABLE_;
    $table_activities = _ACTIVITY_TABLE_;
    $offset = $limit * ($page - 1);
    $PAGE = $page;
    $user = $USER_PAYLOAD['data'];
    $user_id = $user -> user_id;

    if ($q){

        $query = "SELECT COUNT($table_doc.id)
             FROM $table_doc
             INNER JOIN $table_activities
             ON $table_doc.act_id = $table_activities.id
             WHERE user_id = $user_id
             AND mess LIKE '%s';";


        $query = $wpdb -> prepare(
            $query, array(
                "%$q%"
            )
        );

        $DATA_COUNT = $wpdb -> get_var($query);


        $query = "SELECT $table_doc.id, date_created, category, state
             FROM $table_doc
             INNER JOIN $table_activities
             ON $table_doc.act_id = $table_activities.id
             WHERE user_id = $user_id
             AND mess LIKE '%s'
             ORDER BY date_created DESC
             LIMIT $limit";

        $query = $wpdb -> prepare(
            $query, array(
                "%$q%"
            )
        );

        $results = $wpdb -> get_results($query, OBJECT);

        return $results;
    }

    $query = "SELECT COUNT($table_doc.id)
             FROM $table_doc
             INNER JOIN $table_activities
             ON $table_doc.act_id = $table_activities.id
             WHERE user_id = $user_id;";

    $DATA_COUNT = $wpdb -> get_var($query);

    $query = "SELECT $table_doc.id, date_created, category, state
             FROM $table_doc
             INNER JOIN $table_activities
             ON $table_doc.act_id = $table_activities.id
             WHERE user_id = $user_id
             ORDER BY date_created DESC
             LIMIT $limit;";

    $results = $wpdb -> get_results($query, OBJECT);
    return $results;
}

function getAllDocTemp($doc){
    $status = _COMPLETED_;
    $date = time_elapsed_string($req -> date_created);
    $doc_id = $doc -> id;
    $status_color = get_color($status);
    $category = $doc -> category;
    $state = $doc -> state;
    $mess = "Created a document under $category in $state state";

    return (
        "
         <td style=\"width: 5%\">
           <p class=\"media-icon\">
             <span class=\"icon $status_color\">
               <i class=\"fa fa-circle\"></i>
             </span>
          </p>
         </td>

        <td  style=\"width: 85%\">
           <p>$mess</p>
       </td>
       <td  style=\"width: 10%; padding-top: 1.5rem\">
         <small class=\"has-text-centered\">$date</small>
       </td>
        "
    );
}


function getDetailCreatDoc($doc_id){
    global $wpdb;

    $table_doc = _DOC_TABLE_;
    $table_users = _USER_TABLE_;
    $table_activities = _ACTIVITY_TABLE_;

    $query = "SELECT $table_doc.id, $table_activities.date_created, full_name, state, request_type, category, file_name
             FROM $table_doc
             INNER JOIN $table_activities
             ON $table_doc.act_id = $table_activities.id
             INNER JOIN $table_users
             ON $table_activities.user_id = $table_users.id
             WHERE $table_doc.id = $doc_id
             LIMIT 1;";


    $results = $wpdb -> get_results($query, OBJECT);
    return $results[0];
}

function getCreDocDetailTemp($doc){
    $state = $doc -> state;
    $request_type = $doc -> request_type;
    $date = time_elapsed_string($doc -> date_created);
    $doc_id = $doc -> id;
    $category = $doc -> category;
    $full_name = $doc -> full_name;
    $file_name = $doc -> file_name;
    $file_pdf = '/wp-content/themes/clinton-child/assets/generated_documents/' . $file_name . ".pdf";
    $file_image =  '/wp-content/themes/clinton-child/assets/generated_documents/' . $file_name . '.jpg';
    $mess = "Created a document under $category in $state state";

    return(
        "
          <article class=\"media\" style=\"max-width: 85%\">
  <figure class=\"media-left\">
    <p class=\"image is-64x64\">
      <img src=\"https://bulma.io/images/placeholders/128x128.png\">
        </p>
        </figure>
        <div class=\"media-content\">
        <div class=\"content\">
        <p>
        <strong>$full_name</strong> &middot; <small>$date</small>
        <br>
           $mess
        </p>
        <div class=\"columns\">
            <div class=\"column\">
               <strong>Category: </strong> $category
            </div>
            <div class=\"column\">
               <strong>State: </strong> $state
            </div>
        </div>
        <p class=\"hs-text-centered\">
           <a class=\" button is-primary is-fullwidth\" download=\"propellegal-document\" href=\"$file_pdf\">Download PDF File</a>
        </p>
        <p class=\"box\">
           <img class=\"image is-128-128\" src=\"$file_image\">
        </p>

        </div>
        </div>
        </article>
        "
    );
}

function getUserDetails(){
    global $wpdb;
    global $USER_PAYLOAD;

    $user = $USER_PAYLOAD['data'];
    $user_id = $user -> user_id;
    $table_users = _USER_TABLE_;

    $query = "SELECT id, date_created, full_name, email, role_auth, activated
             FROM $table_users
             WHERE $table_users.id = $user_id
             LIMIT 1;";


    $results = $wpdb -> get_results($query, OBJECT);
    return $results[0];
}
