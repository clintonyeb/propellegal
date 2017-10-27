<?php
global $USER_PAYLOAD;
global $PAGE;
global $DATA_COUNT;
$page = "";
$query = "";
parse_str($_SERVER['QUERY_STRING']);

$limit = 20;
$user = $USER_PAYLOAD['data'];
$requests = getAllDocReviews($limit, $page, $query);
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
        Documents Reviewed
    </h2>

    <div class="columns">
        <div class="column is-4">
            
            
            <div class="box has-yellow-top">
                <p class="label">Search Documents</p>
                <div class="field has-addons">
                    <div class="control is-expanded">
                        <input class="input" type="text" id="request-search" placeholder="Search here for documents reviewed">
                    </div>
                    <div class="control" id="req-search-btn">
                        <a class="button is-warning">
                            Search
                        </a>
                    </div>
                </div>
                <hr />
                <p class="label">What is this?</p>
                This page displays a history of all your activities whiles using our services. You can view them anytime to know what you did and when you did it.
            </div>

            <p class="has-text-centered margined-top-down">
	        <a class="button is-primary is-medium" href="/user/ask_attorney">Review your Documents</a>
            </p>
        </div>
        <div class="column">
            <div class="box has-blue-top">
                <div class="level">
                    <div class="level-left">
                        <h3 class="title is-5">Documents</h3>
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
                        <div class="select" style="margin-bottom: .5em">
                            <select>
                                <option>Most Recent</option>
                                <option>Least Recent</option>
                            </select>
                        </div>
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
                            echo ("<p class=\"has-text-centered has-text-darker-blue\">You have not sent any documents for review so far...</p>");
                        } else {
                            foreach($requests as $req){
                                echo('<tr data-href=/user/document_details/?req_id=' . $req -> id . ' class=clickable>');
                                echo (getAllDocReviewstemplate($req));
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
