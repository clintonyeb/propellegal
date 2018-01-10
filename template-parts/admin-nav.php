<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$avatar = getAvatar();
$avatar_name = $avatar -> avatar_name;
$requests = getAllRequests(20);
?>

<aside class="menu is-primary">
    <p class="has-text-centered">
        <a>
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
            <a href="/admin" data-href="admin">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-tachometer"></i>
                </span>
                &nbsp;&nbsp;General
            </a>
        </li>
        <li class="menu-item">
            <a href="/admin/contacts" data-href="contacts">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-comment"></i>
                </span>
                &nbsp;&nbsp;Contacts
            </a>
        </li>
    </ul>

    <p class="menu-label has-text-darker-blue">
        USER
    </p>
    <ul class="menu-list">

        <li class="menu-item">
            <a href="/admin/documents_created" data-href="documents_created">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-book"></i>
                </span>
                &nbsp;&nbsp;Documents
            </a>
        </li>


        <li class="menu-item">
            <a href="/admin/attorney_requests" data-href="attorney_requests">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-handshake-o"></i>
                </span>
                &nbsp;&nbsp;Requests
            </a>
        </li>

        <li class="menu-item">
            <a href="/document_reviews" data-href="document_reviews">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-file"></i>
                </span>
                &nbsp;&nbsp;Uploads
            </a>
        </li>

        <li class="menu-item">
            <a href="/business_registrations" data-href="business_registrations">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-briefcase"></i>
                </span>
                &nbsp;&nbsp;Registrations
            </a>
        </li>

        <li class="menu-item">
            <a href="/admin/user_accounts" data-href="user_accounts">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-user-o"></i>
                </span>
                &nbsp;&nbsp;Accounts
            </a>
        </li>
    </ul>

    <p class="menu-label has-text-darker-blue">
        LAWYER
    </p>
    <ul class="menu-list">
        <li class="menu-item">
            <a href="/admin/lawyer_accounts" data-href="lawyer_accounts">
                <span class="icon is-small has-text-darker-yellow">
                    <i class="fa fa-user-o"></i>
                </span>
                &nbsp;&nbsp;Accounts
            </a>
        </li>
    </ul>

    <p class="menu-label has-text-darker-blue">
        ACCOUNT
    </p>

    <ul class="menu-list">
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
