<?php
$activities = getActivities(30);
?>

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
