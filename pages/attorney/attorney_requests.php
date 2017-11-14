<?php
global $USER_PAYLOAD;
global $PAGE;
global $DATA_COUNT;
$page = "";
$query = "";
parse_str($_SERVER['QUERY_STRING']);

$limit = 20;
$user = $USER_PAYLOAD['data'];
$results = getLawyerRequests($limit, $page, $query);
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

<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>

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
                        <input class="input" type="text" id="request-search" placeholder="Search here for documents reviewed" value="<?php echo $query ?>">
                    </div>
                    <div class="control" id="req-search-btn" data-url="/attorney/attorney_requests">
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
                    <tbody>
                        <?php
                        $c = count($results);
                        if ($c < 1){
                            echo ("<p class=\"has-text-centered has-text-darker-blue\">No documents found...</p>");
                        } else {
                          foreach($results as $req){
                            $req_id = $req -> id;
                            $req_status = $req -> status;
                            $req_feedback = $req -> feedback;

                            $str = "<tr data-href=/attorney/request_messages/?req_id=$req_id&req_status=$req_status&req_feedback=$req_feedback class=clickable>";
                                echo($str);
                                echo (getAllRequestsTemplate($req));
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
