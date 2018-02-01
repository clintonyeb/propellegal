<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;
?>

<span data-href="attorney_requests"></span>
<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>
<section class="section">
  <h2 class="title is-4">
    Ask an Attorney
  </h2>

  <div class="columns">
    <div class="column">
      <div class="box has-top-yellow">
        <article class="media">
          <figure class="media-left">
            <p class="media-icon">
              <span class="icon has-text-darker-yellow">
                <i class="fa fa-hand-o-right"></i>
              </span>
            </p>
          </figure>
          <div class="media-content">
            <div class="content">
              <p>
                Send our attorneys a message
              </p>
            </div>
          </div>
        </article>

        <article class="media">
          <figure class="media-left">
            <p class="media-icon">
              <span class="icon has-text-darker-yellow">
                <i class="fa fa-hand-o-right"></i>
              </span>
            </p>
          </figure>
          <div class="media-content">
            <div class="content">
              <p>
                Our Attorneys review and provide you feedback
              </p>
            </div>
          </div>
        </article>

        <article class="media">
          <figure class="media-left">
            <p class="media-icon">
              <span class="icon has-text-darker-yellow">
                <i class="fa fa-hand-o-right"></i>
              </span>
            </p>
          </figure>
          <div class="media-content">
            <div class="content">
              <p>
                We notify you when a response is ready
              </p>
            </div>
          </div>
        </article>
        <hr />
        <p class="label">What is this?</p>
        This page displays a history of all your activities whiles using our services. You can view them anytime to know what you did and when you did it.
      </div>

      <p class="has-text-centered margined-top-down">
	<a class="button is-primary is-medium" href="/user/attorney_requests">See all requests</a>
      </p>
    </div>
    <div class="column is-8">
      <div class="box has-top-blue">
        <div class="level">
          <div class="level-left">
            <h3 class="title is-5">Create a new Request</h3>
          </div>
        </div>

        <article class="media">
          <figure class="media-left">
            <p class="image is-64x64">
              <img src="<?php echo get_stylesheet_directory_uri() . '/assets/avatar/' . $avatar_name; ?>" height="55" width="55">
            </p>
          </figure>
          <div class="media-content">
            <div class="field">
              <p class="control">
                <textarea id="request-textbox" data-new=true class="textarea" placeholder="I will like to ask if you are available for a case..." cols="10" rows="15"></textarea>
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
      </div>
    </div>
  </div>
</section>
