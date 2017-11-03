<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;
parse_str($_SERVER['QUERY_STRING']);

$bus_reg = getRegDetails($req_id);
$messages = getRegMessages($req_id);
?>

<span data-href="business_registrations"></span>

<section class="section" id="user_activities">
    <h2 class="title is-3">
        Registration Details
    </h2>

    <div class="box has-blue-top">
        <div class="level">
            <div class="level-left">
                <a class="button is-warning" href="/user/business_registrations">
                    <span class="icon">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <span> Go To Registrations</span>
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
        echo getRegDetailsTemp($bus_reg);
        foreach($messages as $mess){
            echo (getRevMesstemplate($mess));
        }
        ?>

        <hr >
        <h4 class="title is-6 has-text-centered" id="admin-action">Actions you can perfrom on request</h4>
        <div class="columns is-multiline">
            <div class="column">
                <p class="has-text-centered">
                <a class="button is-primary is-outlined" data-action="send_message">Send Message</a>
            </p>
            </div>

            <div class="column">
                <p class="has-text-centered">
                <a class="button is-primary is-outlined" data-action="mark_completed">Mark Completed</a>
            </p>
            </div>
            <div class="column">
                <p class="has-text-centered">
                <a class="button is-primary is-outlined" data-action="remove_request">Remove Request</a>
            </p>
            </div>
        </div>

        <input type=hidden value="REGISTER_BUSINESS" id="action-type">

        <article class="media is-hidden" id="reply-box">
            <figure class="media-left">
                <p class="image is-64x64">
                    <img src="<?php echo get_stylesheet_directory_uri() . '/assets/avatar/' . $avatar_name; ?>" height="55" width="55">
                </p>
            </figure>
            <div class="media-content">
                <div class="field">
                    <p class="control">
                        <textarea id="review-textbox" class="textarea" placeholder="Send a reply to the user..."></textarea>
                    </p>
                </div>
                <nav class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <a class="button is-primary is-medium" id="req-submit" data-url="reg_mess">Send Message</a>
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
</section>
