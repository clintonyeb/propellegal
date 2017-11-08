<section class="section is-mfullheight" id="upload-doc">
  <div class="container-fluid">
    <div class="columns">
      <div class="column is-4">
        <h1 class="title is-3">
          Upload your Document
        </h1>
        <ul class="todo">
          <li>
            <span class="icon has-text-darker-yellow">
              <i class="fa fa-hand-o-right"></i>
            </span>
            <span>
              You upload your documents
            </span>
          </li>
          <li>
            <span class="icon has-text-darker-yellow">
              <i class="fa fa-hand-o-right"></i>
            </span>
            <span>
              Our Attorneys review and provide you feedback
            </span>
          </li>
          <li>
            <span class="icon has-text-darker-yellow">
              <i class="fa fa-hand-o-right"></i>
            </span>
            <span>
              We notify you when a response is ready
            </span>
          </li>
        </ul>
      </div>
      <div class="column">
        <div class="columns">
          <div class="column">
            <p class="step">
              1.
            </p>
            <h2 class="title is-4">
              Provide Information
            </h2>
          </div>

          <div class="column">
            <p class="step">
              2.
            </p>
            <h2 class="title is-4">
              Upload Documents
            </h2>
          </div>
          <div class="column">
            <p class="step">
              3.
            </p>
            <h2 class="title is-4">
              Confirmation
            </h2>
          </div>
        </div>

        <progress class="progress is-warning" value="2" max="100">2%</progress>

        <div class="upload-box-cont is-hidden">
          <div class="box">
            <form action="" id="details">
              <div class="field">
                <label class="label is-medium has-text-primary">Full Name</label>
                <div class="control">
                  <input autofocus class="input is-medium is-primary" name="user_name" type="text" placeholder="Please provide fullname">
                </div>
              </div>

              <div class="field">
                <label class="label is-medium has-text-primary">Message</label>
                <div class="control">
                  <textarea class="textarea is-medium is-primary" name="content" rows="8" placeholder="Please provide any additional information..."></textarea>
                </div>
              </div>
            </form>
            <br>
            <div class="level">
              <div class="level-left">
              </div>
              <div class="level-right">
                <p class="butts">
              <a class="button is-primary is-large" data-step="1">
                <span>Submit Documents</span>
              </a>
            </p>
              </div>
            </div>

          </div>
        </div>

        <section class="upload-box-cont is-hidden">
          <div class="box" id="file-box">
            <div class="" ondrop="dropEvent(event)" ondragover="dragOverEvent(event)" ondragleave="dragLeave(event)">
              <input class="file-input" type="file" name="document" id="file-upload" multiple>

              <div class="content">
                <h2 class="title is-3 has-text-centered">
                  <span class="is-hidden-desktop">Tap </span>
                  <span class="is-hidden-touch">Click </span>to upload
                </h2>
                <h2 class="subtitle is-5 has-text-centered has-text-primary is-hidden-touch" >
                  OR
                </h2>
                <h2 class="title is-3 has-text-centered is-hidden-touch">
                  Drag and Drop your files here
                </h2>

                <p class="has-text-centered is-icon">
                  <span class="icon is-large  has-text-darker-yellow">
                    <i class="fa fa-upload"></i>
                  </span>
                </p>
              </div>
            </div>

            <hr>
            <div class="has-text-centered">
              <p class="in">
                <a class="button is-primary is-large" data-step="2">
                  <span>Continue Upload</span>
                  <span class="icon">
                    <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </p>
            </div>

          </div>

          <div class="files is-hidden" id="display-files">
            <h2 class="title is-5">
              Number of documents added: <span id="doc-count" class="has-text-darker-yellow">0</span>
            </h2>

            <div id="docs" class="tags"></div>

          </div>
        </section>


        <div class="upload-box-cont is-hidden">
          <div>
            <h2 class="title is-4">
              Upload Complete
            </h2>
            <p>
              We have received your documents. We will notify you once our lawyers have reviewed it.
            </p>
            <hr><br>
            <p class="is-primary">
              <a class="button is-primary is-large" data-step="3">
                <span class="icon">
                  <i class="fa fa-home"></i>
                </span>
                <span>Go to Homepage</span>
              </a>
            </p>
          </div>
        </div>
        <article class="message is-hidden">
          <div class="message-body">
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
