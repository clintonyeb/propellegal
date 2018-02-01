<?php
parse_str($_SERVER['QUERY_STRING']);

$activities = getActivitiesForUser($req_id, 30);
?>

<span data-href="user_accounts"></span>
<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>

<section class="section" id="activity-history">
    <h2 class="title is-3">
        User Activity History
    </h2>

    <?php
    foreach($activities as $act){
    echo getActivitytemplate($act, $act -> full_name);
    }
    ?>
</section>
