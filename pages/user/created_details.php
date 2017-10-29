<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];

parse_str($_SERVER['QUERY_STRING']);

$doc_reg = getDetailCreatDoc($req_id);
?>

<span data-href="created_details"></span>

<section class="section" id="user_activities">
    <h2 class="title is-3">
        Review Details
    </h2>
    
    <div class="box has-blue-top">
        <div class="level">
            <div class="level-left">
                <a class="button is-warning" href="/user/documents_created">
                    <span class="icon">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <span> Go To Documents</span>
                </a>
            </div>
        </div>
        <hr>
        <?php
        echo getCreDocDetailTemp($doc_reg);
        ?>

    </div>
    </div>
</section>
