<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$user_full = getUserDetails();
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;
?>

<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>

<section class="section" id="user_profile">
    <h2 class="title is-4">
        Profile
    </h2>

    <div class="columns box">
        <div class="column is-4">
            <div class="has-top-yellow">
                <p class="has-text-centered">
                    <img alt="User Image" src="<?php echo get_stylesheet_directory_uri() . '/assets/avatar/' . $avatar_name; ?>" height="200px" class="avatar" style="height: 200px" />
                </p>
            </div>
            <br>
            <div class="file is-centered is-primary is-medium">
                <label class="file-label">
                    <input class="file-input" type="file" name="resume" id="avatar-upload" accept="image/*">
                    <span class="file-cta">
                        <span class="file-icon">
                            <i class="fa fa-upload"></i>
                        </span>
                        <span class="file-label">
                            Upload Avatar
                        </span>
                    </span>
                </label>
            </div>
            <br />
            <article class="message is-small is-hidden">
                <div class="message-body">
                </div>
            </article>

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
            <p class="margined-top-down">
	        <a class="button is-primary is-medium" href="/recover-pass" target="_blank">Change Password</a>
            </p>
        </div>

    </div>
</section>
