<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$requests = getAllRequests(20);
?>

<aside class="menu is-primary">
    <p class="has-text-centered">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/person.jpg" alt="logo brand" class="user-avatar"><br>
        <b><?php echo ($user -> full_name) ?></b>
    </p>
    <hr class="is-marginless" />
    <p class="menu-label has-text-darker-blue">
        MAIN
    </p>
    <ul class="menu-list">
        <li class="menu-item">
            <a href="/user" data-href="user">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-tachometer"></i>
                </span>
                &nbsp;&nbsp;My Dashboard
            </a>
        </li>
        <li class="menu-item">
            <a href="/user/notifications" data-href="notifications">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-bell"></i>
                </span>
                &nbsp;&nbsp;My Notifications
            </a>
        </li>
        
    </ul>

    <p class="menu-label has-text-darker-blue">
        ACTIVITIES
    </p>
    <ul class="menu-list">

        <li class="menu-item">
            <a href="/user/attorney_requests" data-href="attorney_requests">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-handshake-o"></i>
                </span>
                &nbsp;&nbsp;Attorney Requests
            </a>

            <ul>
                <li>
                    <a href="/user/ask_attorney" data-href="ask_attorney">Ask an Attorney</a>
                </li>
            </ul>
        </li>

        <li class="menu-item">
            <a href="/document_review" data-href="document_review">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-file"></i>
                </span>
                &nbsp;&nbsp;Document Reviews
            </a>

            <ul>
                <li>
                    <a href="">Review Document</a>
                </li>
            </ul>
        </li>

        <li class="menu-item">
            <a href="/business_registration" data-href="business_registration">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-briefcase"></i>
                </span>
                &nbsp;&nbsp;Business Registrations
            </a>

            <ul>
                <li>
                    <a href="">Register Business</a>
                </li>
            </ul>
            
        </li>
    </ul>

    <p class="menu-label has-text-darker-blue">
        ACCOUNT
    </p>

    <ul class="menu-list">

        <li class="menu-item">
            <a>
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-user"></i>
                </span>
                &nbsp;&nbsp;Your Profile
            </a>
        </li>

        <li class="menu-item">
            <a>
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-calendar"></i>
                </span>
                &nbsp;&nbsp;Subscription Plans
            </a>
        </li>
        <li class="menu-item">
            <a href="/user_activities" data-href="user_activities">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-paper-plane"></i>
                </span>
                &nbsp;&nbsp;Activity History
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
