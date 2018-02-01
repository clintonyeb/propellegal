<?php
global $USER_PAYLOAD;
global $PAGE;
global $DATA_COUNT;
$user = $USER_PAYLOAD['data'];
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;
$page_id = 1;
$limit = 20;
parse_str($_SERVER['QUERY_STRING']);
$messages = getContactMessages($limit, $page_id);

$back = "";
$forward = "";

if ($PAGE > 1){
  $page = $PAGE - 1;
  $back = "href=\"/admin/contacts/?";
  $back .= "page=$page\"";
}

if ($limit * $PAGE < $DATA_COUNT){
  $page = $PAGE + 1;
  $forward = "href=\"/admin/contacts/?";
  $forward .= "page=$page\"";
}
?>

  <span data-href="attorney_requests"></span>
  <a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>

  <section class="section" id="user_activities">
    <div class="level">
      <div class="level-left">
        <h2 class="title is-4">
          Contact Messages
        </h2>
      </div>
    </div>

    <div class="box has-blue-top">
      <div class="level">
        <div class="level-left">
          <h3 class="title is-5">Messages</h3>
        </div>
        <div class="level-right">
          <p class="">
            <span class="icon is-medium">
              <i class="fa fa-refresh"></i>
            </span>
          </p>
          <p class="field">
            <a class="button page-button" <?php echo( "$back") ?> >
              <span class="icon is-small">
                <i class="fa fa-arrow-left"></i>
              </span>
            </a>
            <a class="button page-button" <?php echo( "$back") ?>>
              <span class="icon is-small">
                <i class="fa fa-arrow-right"></i>
              </span>
            </a>
          </p>
        </div>
      </div>

      <?php
        foreach($messages as $mess){
          echo (getContactMessagesTemplate($mess));
        }
        ?>
    </div>
  </section>