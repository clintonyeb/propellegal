<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;
?>

  <span data-href="user_profile"></span>

  <a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>
  <section class="section">
    <h2 class="title is-4">
      Change Account Password
    </h2>

    <div class="columns">
      <div class="column">
        <div class="box has-top-yellow">
          <p class="label">What is this?</p>
          This page displays a history of all your activities whiles using our services. You can view them anytime to know what you
          did and when you did it.
        </div>
      </div>
      <div class="column is-8">
        <div class="box has-top-blue">
          <article class="message is-small is-hidden">
            <div class="message-body">
            </div>
          </article>
          <form id="change-pass-form">
            <div class="field">
              <label class="label">Current Password</label>
              <div class="control is-shorter">
                <input class="input" type="password" name="current">
              </div>
            </div>

            <div class="field">
              <label class="label">New Password Password</label>
              <div class="control is-shorter">
                <input class="input" type="password" name="password">
              </div>
            </div>

            <div class="field">
              <label class="label">Repeat New Password</label>
              <div class="control is-shorter">
                <input class="input" type="password" name="confirm">
              </div>
            </div>

            <div class="field is-grouped">
              <p class="control">
                <a class="button is-primary" id="changeUserPassBtn">
                  Change Password
                </a>
              </p>
              <p class="control">
                <a class="button is-light" href="/user/user_profile">
                  Cancel
                </a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>

  </section>