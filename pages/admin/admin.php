<?php
$activities = getAllActivities(20);
$doc_created_count = getActivityCount(_CREATE_DOCUMENT_);
$doc_review_count = getActivityCount(_REVIEW_DOCUMENT_);
$attorney_count = getActivityCount(_ASK_ATTORNEY_);
$register_count = getActivityCount(_REGISTER_BUSINESS_);

?>

<section class="section" id="user-dashboard">
    <div class="container-fluid">
        <h1 class="title is-4">Dashboard</h1>
        <div class="columns">
            <div class="column is-4">
                <div class="box">
                    <h3 class="title is-6">All Activity History</h3>
                    <?php
                    foreach($activities as $act){
                        echo getActivitytemplate($act, $act -> full_name);
                    }
                    $c = count($activities);

                    while($c < 20){
                        echo getActivitytemplate(NULL);
                        $c++;
                    }
                    ?>

                </div>
            </div>
            <div class="column">
            </div>
        </div>
    </div>
</section>
