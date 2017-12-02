<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;

$details = getSubscriptiondetails();
$renewed = $details -> date_renewed;
$expire = $details -> date_expire;
$amount = $details -> amount;
$date_expire = new DateTime($expire);
$date_today = new DateTime("now");
$acc_status = $date_expire > $date_today;
?>

<section class="section">
  <a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>
  <h2 class="title is-4">
    Subscription Information
  </h2>

  <div class="box has-top-light">
    <p>
      <strong>Status: </strong> <?php echo ($acc_status ? 'Active' : 'Not Active'); ?>
    </p>
    <p>
      <strong>Date of Renewal: </strong> <?php echo ($renewed ? $renewed : 'Never'); ?>
    </p>
    <p>
      <strong>Date of Expiry: </strong> <?php echo ($expire ? $expire : 'Never'); ?>
    </p>
  </div>
  <p>
    <a class="button is-primary" href="/user/subscribe" <?php echo $acc_status ? 'disabled' : '' ?>>Renew Subscription</a>
  </p>
</section>
