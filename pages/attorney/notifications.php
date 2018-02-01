<?php
$notifs = getUserNotifs(30);
?>
<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>

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
