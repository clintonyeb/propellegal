<?php
global $USER_PAYLOAD;
global $PAGE;
global $DATA_COUNT;
$page = "";
$query = "";
parse_str($_SERVER['QUERY_STRING']);

$limit = 20;
$user = $USER_PAYLOAD['data'];
$requests = getAllRequests($limit, $page, $query);
$back = "";
$forward = "";

if ($PAGE > 1){
  $page = $PAGE - 1;
  $back = "href=\"/user/attorney_requests/?";
  $back .= "page=$page\"";
}

if ($limit * $PAGE < $DATA_COUNT){
  $page = $PAGE + 1;
  $forward = "href=\"/user/attorney_requests/?";
  $forward .= "page=$page\"";
}
?>

<section class="section">
  <h2 class="title is-4">
    Upload a Document
  </h2>

  <div class="columns">
    <div class="column is-4">


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
                You upload your documents
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
        <hr class="" />
        <p class="label">What is this?</p>
        This page displays a history of all your activities whiles using our services. You can view them anytime to know what you did and when you did it.
      </div>

      <p class="has-text-centered margined-top-down">
	<a class="button is-primary is-medium" href="/user/ask_attorney">View Reviewed Documents</a>
      </p>
    </div>
    <div class="column" id="upload-doc">
      <div class="box has-top-blue">
        <div class="columns">
          <div class="column">
            <p class="step">
              1.
            </p>
            <h2 class="title is-5">
              Provide Information
            </h2>
          </div>

          <div class="column">
            <p class="step">
              2.
            </p>
            <h2 class="title is-5">
              Upload Documents
            </h2>
          </div>
          <div class="column">
            <p class="step">
              3.
            </p>
            <h2 class="title is-5">
              Confirmation
            </h2>
          </div>
        </div>

        <progress class="progress is-warning" value="2" max="100">2%</progress>

        <div class="upload-box-cont is-hidden">
          <div class="">
            <form action="" id="details">
              <div class="field">
                <label class="label has-text-primary">Full Name</label>
                <div class="control">
                  <input autofocus class="input is-primary" name="user_name" type="text" placeholder="Please provide fullname">
                </div>
              </div>

              <div class="field">
                <label class="label has-text-primary">Message</label>
                <div class="control">
                  <textarea class="textarea is-primary" name="content" rows="8" placeholder="Please provide any additional information..."></textarea>
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
                    <span>Continue</span>
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="upload-box-cont is-hidden">
          <div class="" id="file-box">
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
                  <span>Submit Upload</span>
                  <span class="icon">
                    <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </p>
            </div>
          </div>

          <div class="files">
            <h2 class="title is-5">
              Number of documents added: <span id="doc-count" class="has-text-darker-yellow">0</span>
            </h2>

            <div id="docs" class="tags"></div>

          </div>
        </div>
        <div class="upload-box-cont is-hidden">
          <div>
            <h2 class="title is-4">
              Upload Complete
            </h2>
            <p>
              We have received your documents. We will notify you once our lawyers have reviewed it.
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
