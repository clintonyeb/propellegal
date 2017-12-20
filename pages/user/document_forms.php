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

$acc_status = checkSubscription();

?>

<span data-href="create_document"></span>
<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>

<section class="section" id="document-form">
  <h2 class="title is-4">
    Upload a Document
  </h2>

  <?php if (!$acc_status): ?>
    <article class="message is-warning is-hidden" id="subs-mess">
      <div class="message-body">
        Please subscribe before you can and download the document. <a href="/user/subscribe">Renew Here</a>
      </div>
    </article>
  <?php endif; ?>

  <div class="columns">

    <div class="column">
      <div class="box has-blue-top">
        <div class="columns">
          <div class="column">
            <p class="step">
              1.
            </p>
            <h2 class="title is-5">
              General Info
            </h2>
          </div>

          <div class="column">
            <p class="step">
              2.
            </p>
            <h2 class="title is-5">
              Additional Info
            </h2>
          </div>
          <div class="column">
            <p class="step">
              3.
            </p>
            <h2 class="title is-5">
              Download Document
            </h2>
          </div>
        </div>

        <progress class="progress is-warning" value="2" max="100">2%</progress>

        <div class="upload-box-cont">
          <div class="has-padding-top">
            <div class="field is-horizontal has-padding-top">
              <div class="field-label is-normal">
                <label class="label">Fullname:</label>
              </div>
              <div class="field-body">
                <div class="field">
                  <p class="control is-expanded">
                    <input class="input" type="text" id="firstname" placeholder="Firstname">
                  </p>
                </div>
                <div class="field">
                  <p class="control is-expanded">
                    <input class="input" type="text" id="lastname" placeholder="Lastname">
                  </p>
                </div>
              </div>
            </div>

            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Address</label>
              </div>
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    <textarea class="textarea" placeholder="Please provide your address here" id="address"></textarea>
                  </div>
                </div>
                <div class="field">
                  <div class="control">
                    <div class="label">City/Municipal: </div>
                    <input class="input" type="text" name="city" placeholder="Provide your city" id="city">
                  </div>
                </div>
              </div>
            </div>

            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label"></label>
              </div>
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    <div class="label">Country: </div>
                    <input class="input" type="text" name="city" placeholder="Provide your country" id="country">
                  </div>
                </div>
                <div class="field">
                  <div class="control">
                    <div class="label">State: </div>
                    <div class="select">
                      <select name="state" id="state">
                        <option>Select state</option>
                        <option value="state-1">State 1</option>
                        <option value="state-2">State 2</option>
                        <option value="state-3">State 3</option>
                        <option value="state-4">State 4</option>
                      </select>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div class="field is-horizontal has-padding-top">
              <div class="field-label">
              </div>
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    <button class="button is-primary" data-doc="1" id="next_btn">
                      Continue
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="upload-box-cont is-hidden">
          <form name="doc-down" class="doc-form" id="next_btn">

            <div class="margined-top-down">
              <a class="button is-primary is-fullwidth down-doc" <?php echo ($acc_status ? '' : 'disabled'); ?>>Download Document</a>
            </div>

            <h2 class="subtitle is-5 bordered">Document Preview:</h2>

            <div id="doc_prev" class="box">
              <img class="image">
            </div>
          </form>
        </div>

        <article id="err" class="message is-hidden is-small has-margin-top" style="max-width:500px; margin: 1rem auto">
          <div class="message-body">
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
