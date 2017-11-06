<?php
global $USER_PAYLOAD;
global $PAGE;
global $DATA_COUNT;
$page = "";
$query = "";
parse_str($_SERVER['QUERY_STRING']);

$limit = 20;
$user = $USER_PAYLOAD['data'];
$results = getAdminRequests($limit, $page, $query);
$back = "";
$forward = "";

if ($PAGE > 1){
    $page = $PAGE - 1;
    $back = "href=\"/user/attorney_requests/?";
    $back .= "page=$page\"";
}

if ($limit * $PAGE < $DATA_COUNT){
    $page = $PAGE + 1;
    $forward = "href=\"/user/attorney_requests/?";
    $forward .= "page=$page\"";
}
?>

<section class="section" id="user_activities">
    <h2 class="title is-4">
        Attorney Requests
    </h2>

    <div class="columns">
        <div class="column is-4">


            <div class="box has-top-yellow">
                <p class="label">Search Documents</p>
                <div class="field has-addons">
                    <div class="control is-expanded">
                        <input class="input" type="text" id="request-search" placeholder="Search here for documents reviewed">
                    </div>
                    <div class="control" id="req-search-btn" data-url="/admin/attorney_requests">
                        <a class="button is-warning">
                            Search
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box has-top-blue">
                <div class="level">
                    <div class="level-left">
                        <p>
                            <span class="icon has-text-warning">
                                <i class="fa fa-circle"></i>
                            </span>
                            <span>
                                Received
                            </span>
                            <span class="icon has-text-info">
                                <i class="fa fa-circle"></i>
                            </span>
                            <span>
                                Processing
                            </span>
                            <span class="icon has-text-success">
                                <i class="fa fa-circle"></i>
                            </span>
                            <span>
                                Completed
                            </span>
                        </p>
                    </div>
                    <div class="level-right">
                        <p style="margin-right: 1rem;">
                            <a class="button is-primary" disabled id="commit-btn">Commit Changes</a>
                        </p>
                        <p class="has-text-light-gray">
                            <?php
                            $c = count($results);
                            $start = (($PAGE) * $limit) + 1 ;
                            $to = $start - 1 + $c;
                            $s = "$start to $to of $DATA_COUNT";
                            echo($s);
                            ?>
                        </p>
                        &nbsp;&nbsp;&nbsp;
                        <p class="field">
                            <a class="button page-button" <?php echo("$back") ?> >
                                <span class="icon is-small">
                                    <i class="fa fa-arrow-left"></i>
                                </span>
                            </a>
                            <a class="button page-button" <?php echo("$back") ?>>
                                <span class="icon is-small">
                                    <i class="fa fa-arrow-right"></i>
                                </span>
                            </a>
                        </p>
                    </div>
                </div>

                <table class="table is-striped is-hoverable is-fullwidth">
                    <input type=hidden value="ASK_ATTORNEY" id=action>
                    <tbody>
                        <?php
                        $c = count($results);
                        if ($c < 1){
                            echo ("<p class=\"has-text-centered has-text-darker-blue\">No documents found...</p>");
                        } else {
                            foreach($results as $req){
                                echo('<tr data-href=/user/created_details/?req_id=' . $req -> id . '>');
                                echo (getAdminReqTemp($req));
                                echo("</tr>");
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div></div>
</section>
