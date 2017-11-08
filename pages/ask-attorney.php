<section class="section is-mfullheight">
  <div class="container-fluid">
    <div class="columns">
      <div class="column is-4">
        <h1 class="title is-3">
          Ask an Attorney
        </h1>
        <ul class="todo">
          <li class="has-margin-top">
            <span class="icon has-text-darker-yellow is-medium">
              <i class="fa fa-hand-o-right"></i>
            </span>
            <p class="is-inline-block is-4 subtitle">
              You send us your request
            </p>
          </li>
          <li class="has-margin-top">
            <span class="icon has-text-darker-yellow is-medium">
              <i class="fa fa-hand-o-right"></i>
            </span>
            <p class="is-inline-block is-4 subtitle">
              Our Attorneys review your request
            </p>
          </li>
          <li class="has-margin-top">
            <span class="icon has-text-darker-yellow is-medium">
              <i class="fa fa-hand-o-right"></i>
            </span>
            <p class="is-inline-block is-4 subtitle">
              We notify you when a response is ready
            </p>
          </li>
        </ul>
      </div>
      <div class="column">
        <div class="">
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
                  <textarea id="attorney-text-el" data-new=true class="textarea" placeholder="What is the probability that I will win this case?..." cols="10" rows="15"></textarea>
                </p>
              </div>
              <nav class="level">
                <div class="level-left">
                  <div class="level-item">
                    <a class="button is-primary" id="ask-attorney-btn">Send Message</a>
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
            </div>
          </article>
        </div>
      </div>
    </div>
  </div>
</section>
