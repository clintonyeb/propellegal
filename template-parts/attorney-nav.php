<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;
$requests = getAllRequests(20);
?>

<aside class="menu is-primary">
    <p class="has-text-centered">
        <a href="/attorney/attorney_profile">
        <img src="<?php echo get_stylesheet_directory_uri() . '/assets/avatar/' . $avatar_name; ?>" alt="User Avatar" class="user-avatar"><br>
        <b><?php echo ($user -> full_name) ?></b>
        </a>
    </p>
    <hr class="is-marginless" />
    <p class="menu-label has-text-darker-blue">
        MAIN
    </p>
    <ul class="menu-list">
        <li class="menu-item">
            <a href="/attorney" data-href="attorney">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-tachometer"></i>
                </span>
                &nbsp;&nbsp;My Dashboard
            </a>
        </li>


    </uln>

    <p class="menu-label has-text-darker-blue">
        ACTIVITIES
    </p>
    <ul class="menu-list">

        <li class="menu-item">
            <a href="/attorney/attorney_requests" data-href="attorney_requests">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-handshake-o"></i>
                </span>
                &nbsp;&nbsp;Attorney Requests
            </a>
        </li>

        <li class="menu-item">
            <a href="/attorney/document_reviews" data-href="document_reviews">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-file"></i>
                </span>
                &nbsp;&nbsp;Document Reviews
            </a>
        </li>

        <li class="menu-item">
            <a href="/attorney/business_registrations" data-href="business_registrations">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-briefcase"></i>
                </span>
                &nbsp;&nbsp;Business Registrations
            </a>
        </li>
    </ul>

    <p class="menu-label has-text-darker-blue">
        ACCOUNT
    </p>

    <ul class="menu-list">

        <li class="menu-item">
            <a href="/attorney/attorney_profile" data-href="attorney_profile">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-user"></i>
                </span>
                &nbsp;&nbsp;Your Profile
            </a>
        </li>
        <li class="menu-item">
            <a href="/attorney_activities" data-href="attorney_activities">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-paper-plane"></i>
                </span>
                &nbsp;&nbsp;Activity History
            </a>
        </li>
        <li class="menu-item">
            <a href="/attorney/notifications" data-href="notifications">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-bell"></i>
                </span>
                &nbsp;&nbsp;My Notifications
            </a>
        </li>
        <li class="menu-item">
            <a href="/logout">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-lock"></i>
                </span>
                &nbsp;&nbsp;Logout
            </a>
        </li>
    </ul>


</aside>
