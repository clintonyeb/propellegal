<div class="modal" id="feedback-modal">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Request Feedback</p>
        <button class="delete" aria-label="close"></button>
      </header>
      <section class="modal-card-body">
        <div class="field">
          <label class="label">Rating: <span id="rat-fig">0</span> &#47; 5: &nbsp;<span id="rat-mess" class="has-text-darker-yellow">Choose a star</span></label>
          <div class="control has-text-centered">
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x fa-star-o feed-icon" data-index=1></i>
            </a>
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x fa-star-o feed-icon" data-index=2></i>
            </a>
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x fa-star-o feed-icon" data-index=3></i>
            </a>
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x fa-star-o feed-icon" data-index=4></i>
            </a>
            <a class="icon has-text-primary is-large">
              <i class="fa fa-3x fa-star-o feed-icon" data-index=5></i>
            </a>
          </div>
        </div>

        <div class="field has-margin-top">
          <label class="label">Feedback Message (Optional)</label>
          <div class="control">
            <textarea class="textarea" id="rat-comment" placeholder="Your comments will help us improve upon our services"></textarea>
          </div>
        </div>

      </section>
      <footer class="modal-card-foot">
        <button class="button is-danger">Cancel</button>
        <button class="button is-primary" id="rat-submit">Send Feedback</button>
      </footer>
    </div>
  </div>
