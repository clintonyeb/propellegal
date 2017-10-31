<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];

parse_str($_SERVER['QUERY_STRING']);

$messages = getRequestMessages($req_id);
?>

<span data-href="attorney_requests"></span>

<section class="section" id="user_activities">
    <h2 class="title is-3">
        Requests to an Attorney
    </h2>
    
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
                <a class="button is-warning" href="/attorney_requests">
                    <span class="icon">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <span> Go To Requests</span>
                </a>
            </div>
            <div class="level-right">
                <a class="button is-primary" id="reply-focus">
                    <span> Reply To Messages</span>
                    <span class="icon">
                        <i class="fa fa-reply"></i>
                    </span>
                </a>
            </div>
        </div>
        <hr>
        
        <?php
        foreach($messages as $mess){
            echo getRequestMessagesTemplate($mess);
        }
        
        ?>

        <article class="media">
            <figure class="media-left">
                <p class="image is-64x64">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg" height="55" width="55">
                </p>
            </figure>
            <div class="media-content">
                <div class="field">
                    <p class="control">
                        <textarea id="request-textbox" class="textarea" placeholder="Send a reply to the lawyer..."></textarea>
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
