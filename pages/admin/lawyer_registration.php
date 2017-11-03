<?php
global $USER_PAYLOAD;
global $PAGE;
global $DATA_COUNT;
$page = "";
$query = "";
parse_str($_SERVER['QUERY_STRING']);

$limit = 20;
$user = $USER_PAYLOAD['data'];
$requests = getAllBusRegs($limit, $page, $query);
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
        Register a Lawyer
    </h2>

    <div class="columns">
        <div class="column is-2">
        </div>
        <div class="column">
            <div class="box has-top-blue">
                <article class="message is-small is-hidden">
                    <div class="message-body">
                    </div>
                </article>
                <div>
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input class="input" name="email" type="email" placeholder="you@example.com" autofocus id=email>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Full Name</label>
                        <div class="control">
                            <input class="input" type="text" name="name" placeholder="Please provide your fullname" id=fullname>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Gender</label>
                                <div class="control">
                                    <div class="select">
                                        <select id=gender>
                                            <option value="male">Male</option>
                                            <option value=female>Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Location</label>
                                <div class="control">
                                    <div class="select">
                                        <select id=state>
                                            <option value="ny">New York</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Activated</label>
                                <div class="control">
                                    <div class="select">
                                        <select id="activated">
                                            <option value=yes>Yes</option>
                                            <option value=no>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="level">
                        <div class="level-left">
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button class="button is-primary is-medium is-focused" id="lawyer-reg">Create Account</button>
                            </div>
                        </div>
                    </div>
                    <div class="field is-pulled-left">
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-2"></div>
    </div>
</section>
