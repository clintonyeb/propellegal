<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];

       ?>

<span data-href="attorney_requests"></span>

<section class="section" id="user_activities">
    <h2 class="title is-3">
Ask an Attorney
    </h2>
    
    <div class="box has-blue-top">
        <div class="level">
            <div class="level-left">
                <h3 class="title is-5">Create a new Request</h3>
            </div>
        </div>

        <article class="media">
            <figure class="media-left">
                <p class="image is-64x64">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg" height="55" width="55">
                </p>
            </figure>
            <div class="media-content">
                <div class="field">
                    <p class="control">
                        <textarea id="request-textbox" col="5" data-new=true class="textarea" placeholder="Send a reply to the lawyer..."></textarea>
                    </p>
                </div>
                <nav class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <a class="button is-primary is-medium" id="req-submit">Send Message</a>
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
                <input name="req_id" id="req_id" type="hidden" value="<?php echo $req_id ?>" />
            </div>
        </article>
        
    </div>
    </div>
</section>
