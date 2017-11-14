<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;
$req_status = false;
$req_feedback = 0;
parse_str($_SERVER['QUERY_STRING']);
$messages = getRequestMessages($req_id);
if($req_feedback){
  $feedback  = getRequestFeedback($req_id, _ASK_ATTORNEY_);
  $rating = $feedback -> rating;
  $feed_comment = $feedback -> content;
}
?>

<span data-href="attorney_requests"></span>

<section class="section" id="user_activities">
  <div class="level">
    <div class="level-left">
      <h2 class="title is-3">
        Requests to an Attorney
      </h2>
    </div>
    <div class="level-right">
      <p class="" id="admin-action">
        <a class="button is-primary is-outlined" data-action="mark_completed"><?php echo ($req_status == _COMPLETED_ ? "Request Completed" : "Mark as Complete"); ?> </a>
      </p>
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
      </div>
    </div>

    <div class="level">
      <div class="level-left">
        <a class="button is-warning" href="/attorney/attorney_requests">
          <span class="icon">
            <i class="fa fa-angle-left"></i>
          </span>
          <span> Go To Requests</span>
        </a>
      </div>

      <?php if ($req_status == _PROCESSING_): ?>

      <div class="level-right">
        <a class="button is-primary" id="reply-focus">
          <span> Reply To Messages</span>
          <span class="icon">
            <i class="fa fa-reply"></i>
          </span>
        </a>
      </div>

      <?php endif ?>

    </div>
    <hr>

    <?php
    foreach($messages as $mess){
      $files = get_req_files($mess -> id, _ASK_ATTORNEY_);
      echo getRequestMessagesTemplate($mess, $files);
    }

    ?>

    <?php if ($req_status == _PROCESSING_): ?>

    <article class="media">
      <figure class="media-left">
        <p class="image is-64x64">
          <img src="<?php echo get_stylesheet_directory_uri() . '/assets/avatar/' . $avatar_name; ?>" height="55" width="55">
        </p>
      </figure>
      <div class="media-content">
        <div class="field">
          <p class="control">
            <textarea id="request-textbox" class="textarea" placeholder="Send a reply to the user..."></textarea>
          </p>
        </div>
        <nav class="level">
          <div class="level-left">
            <div class="level-item">
              <a class="button is-primary" id="req-submit">Send Message</a>
            </div>
            <div class="level-item">
              <div class="file">
                <label class="file-label">
                  <input class="file-input" type="file" id="req-file" multiple>
                  <span class="file-cta">
                    <span class="file-icon">
                      <i class="fa fa-paperclip has-text-primary"></i>
                    </span>
                  </span>
                </label>
              </div>
            </div>
          </div>
          <div class="level-right">
            <div class="level-item">
              <label class="checkbox">
                <input type="checkbox" id="enter-send"> Press enter to send message
              </label>
            </div>
          </div>
        </nav>
        <div class="files is-hidden" id="display-files">
          <h2 class="subtitle is-6">
            Files count: <span id="doc-count" class="has-text-darker-yellow">0</span>
          </h2>

          <div id="docs" class="tags"></div>

        </div>

      </div>
    </article>

    <?php endif ?>

    <?php if($req_feedback) : ?>
      <div class="control has-text-centered">
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x feed-icon <?php echo ( $rating >= 1 ? 'fa-star' : 'fa-star-o') ?>" data-index=1></i>
            </a>
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x feed-icon <?php echo ( $rating >= 2 ? 'fa-star' : 'fa-star-o') ?>" data-index=2></i>
            </a>
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x feed-icon <?php echo ( $rating >= 3 ? 'fa-star' : 'fa-star-o') ?>" data-index=3></i>
            </a>
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x feed-icon <?php echo ( $rating >= 4 ? 'fa-star' : 'fa-star-o') ?>" data-index=4></i>
            </a>
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x feed-icon <?php echo ( $rating >= 5 ? 'fa-star' : 'fa-star-o') ?>" data-index=5></i>
            </a>
      </div>
      <p class="has-text-centered">
        <?php echo ($feed_comment != 'false' ? $feed_comment : '');  ?>
      </p>
    <?php endif ?>

  </div>
  </div>
  <input name="req_id" id="req_id" type="hidden" value="<?php echo $req_id ?>" />
  <input type=hidden value="ASK_ATTORNEY" id="action-type" data-role="attorney"/>
</section>