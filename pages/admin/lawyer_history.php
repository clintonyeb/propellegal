<?php
parse_str($_SERVER['QUERY_STRING']);

$activities = getActivitiesForUser($req_id, 30);
?>

<span data-href="lawyer_accounts"></span>

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
