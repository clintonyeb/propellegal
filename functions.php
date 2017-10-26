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

    $hashed_password = crypt($password, SALT_PASSWORD);

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
        
        if (hash_equals($hashed_password, crypt($password, $hashed_password))) {
            $token = encryptData($res_data);

            setcookie('token', $token, time() + (86400 * 30 * 7), "/"); // 86400 = 7 days

            addActivity(_LOGGED_IN_, $res_data -> id);
            
            wp_send_json(array(
                'message' => "Login Success",
                'status' => true,
                'token' => $token
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
        
        $hashed_password = crypt($password, SALT_PASSWORD);
        
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
    $category = $_POST['category'];
    $user_name = $_POST['user_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $doc_name = $_POST['docName'];

    $m = new Mustache_Engine;
    global $phpWord;


    $path = get_stylesheet_directory() . "/assets/docs/$state/$category";
    $template = $path . "/$doc_name" . '.tpl';

    $tmp_content =  file_get_contents($template);

    $result = $m -> render($tmp_content, array(
        'user_name' => $user_name,
        'category' => $category,
        'address' => $address,
        'city' => $city,
        'country' => $country,
        'state' => $state
    ));

    $section = $phpWord->addSection();

    $section->addText($result);

    $out_path = get_stylesheet_directory() . "/assets/generated_documents/";
    $out_file = uniqid('output-', true);
    
    $gen_file_docx = $out_path . $out_file . '.docx';
    $gen_file_odt = $out_path . $out_file . '.odt';
    $gen_file_html = $out_path . $out_file . '.html';
    $gen_file_pdf = $out_path . $out_file . '.pdf';
    $gen_file_jpg = $out_path . $out_file . '.jpg';

    $docWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $docWriter->save($gen_file_docx);

    /* $odtWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
     * $odtWriter-> save($gen_file_odt);*/

    $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
    $htmlWriter->save($gen_file_html);

    file_put_contents($gen_file_pdf, get_pdf($gen_file_html));

    $pdf = new Spatie\PdfToImage\Pdf($gen_file_pdf);
    $pdf->saveImage($gen_file_jpg);
    
    wp_send_json(array(
        'message' => 'Success',
        'status' => true,
        'data' => $out_file
    ));

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
    $files = $_FILES['file'];
    $name = $_POST['name'];
    $content = $_POST['content'];

    // Authenticate request
    if (trim($_POST['client_key']) != CLIENT_KEY)
        return die(0);

    // TODO: Authenticate user

    $total = count($files['name']);

    for($i = 0; $i < $total; $i++){
        _saveFile($files, $i);
    }


    wp_send_json(array(
        'message' => 'Success',
        'status' => true,
    ));

    die();
}

/* function get_doc(){
 * global $wpdb;
 * global $DOC_TABLE;
 *
 * // TODO: authenticate user
 *
 * $table_doc = $wpdb->prefix. $DOC_TABLE;
 * $category = $_GET['query'];
 *
 * $results = $wpdb -> get_results("SELECT id, category, doc_name, zip_code, file_path FROM $table_doc WHERE category = '$category' LIMIT 20;");
 *
 * wp_send_json(array(
 *     'message' => 'Success',
 *         'status' => true,
 *         'data'=> $results));
 *
 * die();
 * }*/

/* function get_file(){
 * global $wpdb;
 * global $DOC_TABLE;
 *
 * // TODO: Authenticate user
 *
 * $table_doc = $wpdb->prefix. $DOC_TABLE;
 * $id = $_GET['id'];
 *
 * $results = $wpdb -> get_results("SELECT id, category, doc_name, zip_code, doc_name FROM $table_doc WHERE id = '$id' LIMIT 1;");
 *
 * foreach ($results as $data) {
 * $path = get_bloginfo( 'stylesheet_directory' ) . "/assets/docs/" . $data -> zip_code . "/" . $data -> category . "/";
 * $file = $data ->doc_name;
 * $theData = file_get_contents($path.$file);
 *      wp_send_json(array(
 *     'message' => 'Success',
 *         'status' => true,
 *         'data'=> $theData));
 *       exit;
 * }
 *
 * wp_send_json(array(
 *     'message' => 'Failed',
 *         'status' => false));
 *  die();
 * }*/

function _saveFile($files, $i){
    $target_dir =  "assets/uploads/";
    $target_file = $target_dir . basename($files['name'][$i]);
    $uploadOk = 1;

    // TODO: Authenticate user

    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $check = getimagesize($files['tmp_name'][$i]);
    # var_dump($check);

    /*
      if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
      } else {
      echo "File is not an image.";
      $uploadOk = 0;
      }
    */

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($files["tmp_name"][$i], SITE_ROOT . '/' .  $target_file)) {
            wp_send_json(array(
                'message' => 'Success',
                'status' => true));

            # addActivity($UPLOADED_DOCUMENT, $wp_db -> $results[0] -> id);

        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
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
        wp_send_json(array(
            'message' => 'Success',
            'status' => true));
    } else {
        wp_send_json(array(
            'message' => 'Error adding request',
            'status' => false));
    }

    
    die();
}

function ask_business(){
    wp_send_json(array(
        'message' => 'Success',
        'status' => true));
    die();
}

function requestMessage(){

}

function getActivities($limit = 10){
    global $wpdb;

    $table_activities = _ACTIVITY_TABLE_;
    
    $query = "SELECT id, date_created, type_name
             FROM $table_activities
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

function getAllRequests($limit = 20){
    global $wpdb;

    $table_requests = _REQUEST_TABLE_;
    $table_activities = _ACTIVITY_TABLE_;
    
    $query = "SELECT $table_requests.id, last_updated, type_name, mess, viewed, status
             FROM $table_requests
             INNER JOIN $table_activities
             ON $table_requests.act_id = $table_activities.id
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
    
}

function getRequestMessagesTemplate($mess){

}