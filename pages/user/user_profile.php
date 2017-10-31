<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$user_full = getUserDetails();
?>

<section class="section" id="user_activities">
    <h2 class="title is-3">
        Profile
    </h2>

    <div class="columns">
        <div class="column is-narrow">
            
            
            <div class="box has-top-yellow">
                <img alt="User Image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg" width="height" class="is-128x128" />
            </div>

            <p class="has-text-centered margined-top-down">
	        <a class="button is-primary is-medium" href="/recover-pass" target="_blank">Change Password</a>
            </p>
        </div>
        <div class="column">
            <div class="box has-top-blue">
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
</section>
