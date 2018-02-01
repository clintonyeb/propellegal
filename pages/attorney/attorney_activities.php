<?php
$activities = getActivities(30);
?>

<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>

<section class="section" id="activity-history">
    <h2 class="title is-3">
        Activity History
    </h2>

    <?php
    foreach($activities as $act){
    echo getActivitytemplate($act);
    }
    ?>
</section>
