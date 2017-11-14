<?php
$activities = getAllActivities(20);
$doc_created_count = getAllActivityCount(_CREATE_DOCUMENT_);
$doc_review_count = getAllActivityCount(_REVIEW_DOCUMENT_);
$attorney_count = getAllActivityCount(_ASK_ATTORNEY_);
$register_count = getAllActivityCount(_REGISTER_BUSINESS_);
//error_log(print_r(phpinfo(), true));
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
                <div class="columns is-mobile is-multiline">
                    <div class="column">
                        <div class="box is-darker-blue">
                            <a href="/admin/documents_created">
                            <p class="has-text-white has-text-centered">
                                DOCUMENTS CREATED
                            </p>
                            <h3 class="title is-3 has-text-white has-text-centered">
                                <?php echo $doc_created_count ?>
                            </h3>
                            </a>
                        </div>
                    </div>
                    <div class="column">
                        <div class="box is-light-gray">
                            <a href="/admin/document_reviews">
                            <p class="has-text-warning has-text-centered">
                                DOCUMENTS REVIEWED
                            </p>
                            <h3 class="title is-3 has-text-warning has-text-centered">
                                <?php echo $doc_review_count ?>
                            </h3>
                            </a>
                        </div>
                    </div>
                    <div class="column">
                        <div class="box is-darker-yellow ">
                            <a href="/admin/attorney_requests">
                            <p class="has-text-centered">
                                ATTORNEY REQUESTS
                            </p>
                            <h3 class="title is-3 has-text-centered">
                                <?php echo $attorney_count ?>
                            </h3>
                            </a>
                        </div>
                    </div>
                    <div class="column">
                        <div class="box is-darker-gray ">
                            <a href="/admin/business_registrations">
                            <p class="has-text-white has-text-centered">
                                BUSINESS REGS
                            </p>
                            <h3 class="title is-3 has-text-white has-text-centered">
                                <?php echo $register_count ?>
                            </h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <h3 class="title is-5">System Information</h3>
                    <br>
                    <table class="table is-bordered is-striped is-narrow is-fullwidth">
                        <tr>
                            <td>
                                Wordpress Version
                            </td>
                            <td>
                                <?php echo $wp_version ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Current PHP Version
                            </td>
                            <td>
                                <?php echo phpversion();?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Memory Usage
                            </td>
                            <td>
                                <?php echo formatBytes(memory_get_usage(true)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Peak Memory Usage
                            </td>
                            <td>
                                <?php echo formatBytes(memory_get_peak_usage(true)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Operating System
                            </td>
                            <td>
                                <?php echo PHP_OS; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
