<?php
global $USER_PAYLOAD;
global $PAGE;
global $DATA_COUNT;
$page = "";
$query = "";
parse_str($_SERVER['QUERY_STRING']);

$limit = 20;
$user = $USER_PAYLOAD['data'];
$requests = getAttorneyRegs($limit, $page, $query);
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
    <h2 class="title is-3">
        Business Registrations
    </h2>

    <div class="columns">
        <div class="column is-4">


            <div class="box has-top-yellow">
                <p class="label">Search Registrations</p>
                <div class="field has-addons">
                    <div class="control is-expanded">
                        <input class="input" type="text" id="request-search" placeholder="Search here for requests made">
                    </div>
                    <div class="control" id="req-search-btn" data-url="/attorney/business_registrations">
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
                        <h3 class="title is-5">Registrations</h3>
                    </div>
                    <div class="level-right">

                        <p class="reload">
                            <span class="icon is-medium has-text-darker-yellow">
                                <i class="fa fa-refresh"></i>
                            </span>
                        </p>
                    </div>
                </div>

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
                            $c = count($requests);
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
                <br />
                <table class="table is-striped is-hoverable is-fullwidth">
                    <tbody>
                        <?php
                        $c = count($requests);
                        if ($c < 1){
                            echo ("<p class=\"has-text-centered has-text-darker-blue\">No registrations found...</p>");
                        } else {
                          foreach($requests as $req){
                            $req_id = $req -> id;
                            $req_status = $req -> status;
                            $req_feedback = $req -> feedback;

                            $str = "<tr data-href=/attorney/business_detail/?req_id=$req_id&req_status=$req_status&req_feedback=$req_feedback class=clickable>";
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