<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];
$requests = getAllRequests();
?>

<section class="section" id="user_activities">
    <h2 class="title is-3">
        Requests to an Attorney
    </h2>

    <div class="columns">
        <div class="column is-4">
            
            
            <div class="box has-yellow-top">
                <p class="label">Search Requests</p>
                <div class="field has-addons">
                    <div class="control is-expanded">
                        <input class="input" type="text" placeholder="Search here for requests made">
                    </div>
                    <div class="control">
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
	            <a class="button is-primary is-medium" href="/user/ask_attorney">Submit a new request</a>
                </p>
        </div>
        <div class="column">
            <div class="box has-blue-top">
                <div class="level">
                    <div class="level-left">
                        <h3 class="title is-5">Requests</h3>
                    </div>
                    <div class="level-right">
                        <p class="">
                            <span class="icon is-medium">
                                <i class="fa fa-refresh"></i>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="level">
                    <div class="level-left">
                        <div class="select">
                            <select style="border-radius: 10px">
                                <option>Most Recent</option>
                                <option>Least Recent</option>
                                <option value="">Attorney Requests</option>
                                <option value="">Document Reviews</option>
                                <option value="">Business Registrations</option>
                                <option value="">User Logins</option>
                            </select>
                        </div>
                    </div>
                    <div class="level-right">
                        <p>
                            1 to 20 of 2000
                        </p>
                        &nbsp;&nbsp;&nbsp;
                        <p class="field">
                            <a class="button page-button">
                                <span class="icon is-small">
                                    <i class="fa fa-arrow-left"></i>
                                </span>
                            </a>
                            <a class="button page-button">
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
                        foreach($requests as $req){
                            echo('<tr data-href=/user/request_messages/?req_id=' . $req -> id . ' class=clickable>');
                            echo (getAllRequestsTemplate($req));
                            echo("</tr>");
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</section>
