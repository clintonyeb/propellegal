<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];

parse_str($_SERVER['QUERY_STRING']);

$user_full = getUserDetails($req_id);
$avatar = getAvatar($req_id);
$avatar_name = $avatar -> avatar_name;
?>

<span data-href="user_accounts"></span>
<section class="section" id="user_profile">
    <h2 class="title is-4">
        User Profile
    </h2>

    <div class="box">
    <div class="columns">
        <div class="column is-4">
            <div class="has-top-yellow">
                <p class="has-text-centered">
                    <img alt="User Image" src="<?php echo get_stylesheet_directory_uri() . '/assets/avatar/' . $avatar_name; ?>" height="200px" class="avatar" style="height: 200px" />
                </p>
            </div>
        </div>
        <div class="column">
            <div class="has-top-blue">
                <h3 class="title is-5">User Information</h3>

                <p class="has-margin-top">
                    <strong>Email Address: </strong> <?php echo $user_full -> email ?>
                </p>

                <p class="has-margin-top">
                    <strong>Fullname: </strong> <?php echo $user_full -> full_name ?>
                </p>

                <p class="has-margin-top">
                    <strong>Account Created: </strong> <?php echo $user_full -> date_created ?>
                </p>

                <p class="has-margin-top">
                    <strong>Account Activated: </strong> <?php echo 'Yes' ?>
                </p>

                <p class="has-margin-top">
                    <strong>Role: </strong> <?php echo 'User' ?>
                </p>
            </div>
        </div>
    </div>
<hr>
    <div>
        <h4 class="title is-6 has-text-centered" id="admin-action">Actions you can perfrom on user account</h4>
        <div class="columns is-multiline">
            <div class="column">
                <p class="has-text-centered">
                <a class="button is-primary is-outlined" data-action="send_message">Email User</a>
            </p>
            </div>

            <div class="column">
                <p class="has-text-centered">
                <a class="button is-primary is-outlined" data-action="mark_completed">Deactivate Account</a>
            </p>
            </div>
            <div class="column">
                <p class="has-text-centered">
                <a class="button is-primary is-outlined" data-action="remove_request">Remove User</a>
            </p>
            </div>
        </div>

        <input type=hidden value="USER_ACCOUNT" id="action-type">
    </div>

    <article class="media is-hidden" id="reply-box">
            <figure class="media-left">
                <p class="image is-64x64">
                    <img src="<?php echo get_stylesheet_directory_uri() . '/assets/avatar/' . $avatar_name; ?>" height="55" width="55">
                </p>
            </figure>
            <div class="media-content">
                <div class="field">
                    <p class="control">
                        <textarea id="review-textbox" class="textarea" placeholder="Email user..."></textarea>
                    </p>
                </div>
                <nav class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <a class="button is-primary is-medium" id="req-submit" data-url="user_mess">Send Message</a>
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
