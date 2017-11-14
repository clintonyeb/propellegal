<?php
global $USER_PAYLOAD;
$user = $USER_PAYLOAD['data'];

parse_str($_SERVER['QUERY_STRING']);

$doc_reg = getDetailCreatDoc($req_id);
?>

<span data-href="documents_created"></span>
<a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>

<section class="section">
    <h2 class="title is-4">
        Created Document
    </h2>

    <div class="box has-blue-top">
        <div class="level">
            <div class="level-left">
                <a class="button is-warning" href="/admin/documents_created">
                    <span class="icon">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <span> Go To Documents</span>
                </a>
            </div>
        </div>
        <hr>
        <?php
        echo getAdminCreDocDetailTemp($doc_reg);
        ?>
    </div>
</section>
