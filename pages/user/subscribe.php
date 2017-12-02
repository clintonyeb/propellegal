<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;
?>

<span data-href="user_subscription"></span>
<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>
<section class="section">
  <h2 class="title is-4">
    Subscribe
  </h2>

  <div class="columns">
    <div class="column">
      <div class="box has-top-yellow">
        <p class="label">What is this?</p>
        This page displays a history of all your activities whiles using our services. You can view them anytime to know what you did and when you did it.
      </div>
    </div>
    <div class="column is-8">
      <div class="">

        <div id="sq-ccbox">
          <form id="nonce-form" novalidate action="/user/process_payment" method="POST">
            <div class="columns">
              <div class="column is-8">
                <article id="pay-mess" class="message is-warning is-small is-hidden">
                  <div class="message-body">
                    <ul></ul>
                  </div>
                </article>
                <h3 class="title is-6">Pay with a Debit/Credit Card</h3>
                <div class="field is-horizontal">
                  <div class="field-label">
                    <label class="label">Card Number</label>
                  </div>
                  <div class="field-body">
                    <div class="field">
                      <p class="control is-expanded has-icons-right">
                        <div id="sq-card-number"></div>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="field is-horizontal">
                  <div class="field-label">
                    <label class="label">CVV</label>
                  </div>
                  <div class="field-body">
                    <div class="field">
                      <p class="control is-expanded has-icons-right">
                        <div id="sq-cvv"></div>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="field is-horizontal">
                  <div class="field-label">
                    <label class="label">Expiry Date</label>
                  </div>
                  <div class="field-body">
                    <div class="field">
                      <p class="control is-expanded">
                        <div id="sq-expiration-date"></div>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="field is-horizontal">
                  <div class="field-label">
                    <label class="label">Postal Code</label>
                  </div>
                  <div class="field-body">
                    <div class="field">
                      <p class="control is-expanded">
                        <div id="sq-postal-code"></div>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="field is-horizontal">
                  <div class="field-label">
                    <label class="label">Choose Plan</label>
                  </div>
                  <div class="field-body">
                    <div class="field">
                      <div class="control">
                      <div class="select is-fullwidth">
                        <select id="amount" name="amount">
                          <option selected value="100">$100 for 3 months</option>
                          <option value="150">$150 for 5 months</option>
                          <option value="200">$200 for 10 months</option>
                        </select>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>

                <input id="sq-creditcard" class="button is-primary button-credit-card is-pulled-right" type="submit" value="Pay with card">
              </div>
              <div class="column is-4">
                <h4 class="title is-6">Pay with a Digital Wallet</h4>
                <div id="sq-walletbox">
                  <article class="message is-warning is-small" id="sq-masterpass-label">
                    <div class="message-body">
                      Masterpass not enabled
                    </div>
                  </article>
                  <button id="sq-masterpass" class="button-masterpass"></button>
                </div>
              </div>
            </div>
            <input type="hidden" id="card-nonce" name="nonce">
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
