<?php
/**
 * Clinton Child
 *
 * This file serves as the container page for all templates
 *
 * Template Name: Login Page
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
      <i class="fa fa-user"></i>
    </span>
     Login To Account
    </h2>

    <div class="columns">
      <div class="column is-one-quarter">
      </div>
      <div class="column card">
        <article class="message is-small is-hidden">
            <div class="message-body">
            </div>
        </article>
        <div class="label has-text-centered">Don&apos;t have an Account? <u><a href="/register">Sign Up</a></u></div>
        <form name="login-form">
         <div class="field">
           <label class="label is-medium">Email</label>
           <div class="control">
             <input class="input is-medium" name="email" type="email" placeholder="Enter email address" autofocus>
           </div>
         </div>
         <div class="field">
           <label class="label is-medium">Password</label>
           <div class="control">
             <input class="input is-medium" name="password" type="password" placeholder="Enter account password">
           </div>
         </div>
         <hr>
         <div class="level">
             <div class="level-left">
                 <div class="level-item">
                     <div class="label has-text-centered"> <u><a href="/recover-pass">I have forgotten my password</a></u></div>
                  </div>
             </div>
             <div class="level-right">
                 <div class="level-item">
                     <button class="button is-primary is-large is-focused" id="login-submit">Log Me In</button>
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
