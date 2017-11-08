<section class="section is-mfullheight">
  <div class="container-fluid">
    <div class="columns">
      <div class="column is-4">
        <h1 class="title is-3">
          Register Business Firm
        </h1>
        <ul class="todo">
          <li class="has-margin-top">
            <span class="icon has-text-darker-yellow is-medium">
              <i class="fa fa-hand-o-right"></i>
            </span>
            <p class="is-inline-block is-4 subtitle">
              You send us your details
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
      <div class="column" id="register-business">
            <div class="box has-top-blue">
                <div class="columns">
                    <div class="column">
                        <p class="step">
                            1.
                        </p>
                        <h2 class="title is-5">
                            Personal Details
                        </h2>
                    </div>

                    <div class="column">
                        <p class="step">
                            2.
                        </p>
                        <h2 class="title is-5">
                            Business Info
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

                <div class="upload-box-cont">
                    <div class="has-padding-top">
                        <h3 class="subtitle is-4">
                            Primary Contact:
                        </h3>
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

                        <div class="field is-horizontal has-padding-top">
                            <div class="field-label is-normal">
                                <label class="label">Phone number:</label>
                            </div>
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control">
                                            <a class="button is-static">
                                                +01
                                            </a>
                                        </p>
                                        <p class="control is-expanded">
                                            <input class="input" type="tel" id="phone"  placeholder="Your phone number">
                                        </p>
                                    </div>
                                    <p class="help">Do not enter the country code</p>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal has-padding-top">
                            <div class="field-label">
                                <!-- Left empty for spacing -->
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-primary" data-step="1">
                                            Continue
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="upload-box-cont is-hidden">
                    <div class="has-padding-top">
                        <h3 class="subtitle is-4">
                            Business Address
                        </h3>
                        <div class="field is-horizontal has-padding-top">
                            <div class="field-label is-normal">
                                <label class="label">Business Address:</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control is-expanded">
                                        <input class="input" id="address" type="text" placeholder="Address">
                                    </p>
                                </div>
                                <div class="field">
                                    <p class="control is-expanded">
                                        <input class="input" type="text" id="city" placeholder="City">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal has-padding-top">
                            <div class="field-label is-normal">
                                <label class="label"></label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control is-expanded">
                                        <input class="input" type="text" id="state" placeholder="State">
                                    </p>
                                </div>
                                <div class="field">
                                    <p class="control is-expanded">
                                        <input class="input" type="text" id="zip" placeholder="ZIP Code">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal has-padding-top">
                            <div class="field-label is-normal">
                                <label class="label">Business Type</label>
                            </div>
                            <div class="field-body">
                                <div class="field is-narrow">
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select id="bus-type">
                                                <option>Business development</option>
                                                <option>Marketing</option>
                                                <option>Sales</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal has-padding-top">
                            <div class="field-label is-normal">
                                <label class="label">Company Name</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" type="text" id="com-name" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal has-padding-top">
                            <div class="field-label is-normal">
                                <label class="label">Company Description</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" type="text" placeholder="" id="com-desc">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal has-padding-top">
                            <div class="field-label is-normal">
                                <label class="label">Additional Message*</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <textarea class="textarea is-primary" name="content" rows="3" id="mess" rows="8" placeholder="Please provide any additional information..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal has-padding-top">
                            <div class="field-label">
                                <!-- Left empty for spacing -->
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-primary" data-step="2">
                                            Continue
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="message is-hidden is-small has-margin-top" style="max-width:500px; margin: 1rem auto">
                    <div class="message-body">
                    </div>
                </article>
            </div>
        </div>
    </div>
  </div>
</section>
