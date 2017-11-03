<?php
$activities = getActivities(7);
$doc_created_count = getActivityCount(_CREATE_DOCUMENT_);
$doc_review_count = getActivityCount(_REVIEW_DOCUMENT_);
$attorney_count = getActivityCount(_ASK_ATTORNEY_);
$register_count = getActivityCount(_REGISTER_BUSINESS_);

$notifications = array();

$user_full = getUserDetails();
?>

<section class="section" id="user-dashboard">
    <div class="container-fluid">
        <h1 class="title is-3">Dashboard</h1>
        <div class="columns">
            <div class="column">
                <div class="box has-top-blue">
                    <h3 class="title is-5">Recent Notifications</h3>
                    <?php
                    foreach($notifications as $not){
                        echo getActivitytemplate($not);
                    }
                    $c = count($notifications);

                    while($c < 7){
                        echo getActivitytemplate(NULL);
                        $c++;
                    }
                    ?>
                </div>
            </div>
            <div class="column">
                <div class="box has-top-yellow">
                    <h3 class="title is-5">Activities Summary</h3>
                    <?php
                    foreach($activities as $act){
                        echo getActivitytemplate($act);
                    }
                    $c = count($activities);

                    while($c < 7){
                        echo getActivitytemplate(NULL);
                        $c++;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="box is-darker-blue padded-small">
                    <p class="has-text-white has-text-centered">
                        DOCUMENTS CREATED
                    </p>
                    <h3 class="title is-3 has-text-white has-text-centered">
                        <?php echo $doc_created_count ?>
                    </h3>
                    <p class="has-text-centered">
                        <a class="button is-white" href="/user/create_document">Create a Document</a>
                    </p>
                </div>
            </div>
            <div class="column">
                <div class="box is-light-gray padded-small">
                    <p class="has-text-warning has-text-centered">
                        DOCUMENTS REVIEWED
                    </p>
                    <h3 class="title is-3 has-text-warning has-text-centered">
                        <?php echo $doc_review_count ?>
                    </h3>
                    <p class="has-text-centered">
                        <a class="button is-warning" href="/user/document_reviews">Review a Document</a>
                    </p>
                </div>
            </div>
            <div class="column">
                <div class="box is-darker-yellow padded-small">
                    <p class="has-text-centered">
                        ATTORNEY REQUESTS
                    </p>
                    <h3 class="title is-3 has-text-centered">
                        <?php echo $attorney_count ?>
                    </h3>
                    <p class="has-text-centered">
                        <a class="button is-dark" href="/user/attorney_requests">Ask an Attorney</a>
                    </p>
                </div>
            </div>
            <div class="column">
                <div class="box is-darker-gray padded-small">
                    <p class="has-text-white has-text-centered">
                        BUSINESS REGISTRATIONS
                    </p>
                    <h3 class="title is-3 has-text-white has-text-centered">
                        <?php echo $register_count ?>
                    </h3>
                    <p class="has-text-centered">
                        <a class="button is-white" href="/user/register_business">Register a Business</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="box has-top-gray">
                    <h3 class="title is-5">Profile Summary</h3>
                    <p>
                        <strong>Name: </strong> <?php echo $user_full -> full_name ?>
                    </p>
                    <p>
                        <strong>Email Address: </strong> <?php echo $user_full -> email ?>
                    </p>
                    <p>
                        <strong>Date joined: </strong> <?php echo $user_full -> date_created ?>
                    </p>
                </div>
            </div>
            <div class="column">
                <div class="box has-top-light">
                    <h3 class="title is-5">Subscription Summary</h3>
                    <p>
                        <strong>Status: </strong> Not Active
                    </p>
                    <p>
                        <strong>Date of Renewal: </strong> Never
                    </p>
                    <p>
                        <strong>Date of Expiry: </strong> Never
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
