<?php
$notifs = getUserNotifs(30);
?>
<section class="section" id="notifications">
    <h2 class="title is-3">
        Notifications
    </h2>

    <?php
    foreach($notifs as $noti){
    echo getNotifsTemplate($noti);
    }
    ?>
</section>
