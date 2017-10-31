<?php
/**
 * Clinton Child
 *
 * This file serves as the registration page.
 *
 * Template Name: Registration Page
 *
 * @package clinton-child
 * @author  Clinton
 */
?>

<?php
get_template_part('template-parts/nav');
?>
</section>

<section class="section is-mfullheight">
  <div class="container">
    <h2 class="title is-4 has-text-centered">
     <span class="icon is-small is-left">
      <i class="fa fa-user-o"></i>
    </span>
     Register Account
    </h2>

    <div class="columns">
      <div class="column is-one-quarter">
      </div>
      <div class="column card">
         <article class="message is-small is-hidden">
            <div class="message-body">
            </div>
         </article>
         <div class="has-text-centered label">Already have an Account? <u><a href="/login">Login instead</a></u></div>
         <form name="regist-form">
         <div class="field">
           <label class="label">Email</label>
           <div class="control">
             <input class="input" name="email" type="email" placeholder="you@example.com" autofocus>
           </div>
         </div>
         <div class="field">
           <label class="label">Full Name</label>
           <div class="control">
             <input class="input" type="text" name="name" placeholder="Please provide your fullname">
           </div>
         </div>
         <div class="field">
           <label class="label">Account Password</label>
           <div class="control">
             <input class="input" type="password" name="password" placeholder="Enter your account password">
           </div>
         </div>
           <div class="field">
           <label class="label">Confirm Password</label>
           <div class="control">
               <input class="input" type="password" name="cpassword" placeholder="Repeat Password" />
           </div>
         </div>
         <div class="field">
             <div class="control">
                 <label class="checkbox">
                     <input type="checkbox" name="terms">
                     I agree to the <a href="/terms" target="_blank">terms and conditions</a>
                 </label>
             </div>
         </div>
         <hr>
         <div class="level">
             <div class="level-left">
                 <div class="level-item">
                     <div class="g-recaptcha" data-sitekey="6LeW0TIUAAAAAGMxUstigP1-zQOG0BaAkHrcj6tG"></div>
                 </div>
             </div>
             <div class="level-right">
                 <div class="level-item">
                     <button class="button is-primary is-large is-focused" id="reg-submit">Create Account</button>
                 </div>
           </div>
         </div>
          <div class="field is-pulled-left">
         </div>
         </form>
      </div>
      <div class="column is-one-quarter">

      </div>
    </div>
  </div>
</section>

<script src='https://www.google.com/recaptcha/api.js'></script>

<?php
get_template_part('template-parts/footer');
?>
