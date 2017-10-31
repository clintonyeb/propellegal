<?php
/**
 * Clinton Child
 *
 * This file serves as the container page for all templates
 *
 * Template Name: Recover Account Page
 *
 * @package clinton-child
 * @author  Clinton
 */
?>

<?php
// recovery code
global $otp_key;
$auth = '';

parse_str($_SERVER['QUERY_STRING']);

if (!$auth) return die(0);

$data = decode_jwt($auth, $otp_key, true);
if (!$data) return die(0);


get_template_part('template-parts/nav');
?>

</section>

<section class="section">
  <div class="container">
    <h2 class="title is-4 has-text-centered">
     <span class="icon is-small is-left">
      <i class="fa fa-user"></i>
    </span>
     Create New Password
    </h2>

    <div class="columns">
      <div class="column is-one-quarter">
      </div>
      <div class="column card">
        <article class="message is-small is-hidden">
            <div class="message-body">
            </div>
        </article>
        <form name="login-form">
         <div class="field">
           <label class="label is-medium">Email</label>
           <div class="control">
             <input class="input is-medium" value="<?php echo $data -> data -> email ?>" disabled name="email">
           </div>
         </div>
         <div class="field">
           <label class="label is-medium">Password</label>
           <div class="control">
             <input class="input is-medium" name="password" type="password" placeholder="Enter account password" autofocus>
           </div>
         </div>
         <div class="field">
           <label class="label is-medium">Confirm Password</label>
           <div class="control">
             <input class="input is-medium" name="cpassword" type="password" placeholder="Confirm account password">
           </div>
         </div>
         <hr>
         <div class="level">
             <div class="level-left">
             </div>
             <div class="level-right">
                 <div class="level-item">
                     <button class="animated button is-primary is-large is-focused" id="change-pass">Change Password</button>
                 </div>
           </div>
         </div>
    </form>
      </div>
      <div class="column is-one-quarter">
      </div>
    </div>
  </div>
</section>

<?php
get_template_part('template-parts/footer');
?>
