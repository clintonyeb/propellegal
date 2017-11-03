<?php
/**
 * Clinton Child
 *
 * This file serves as the container page for all templates
 *
 * Template Name: LAWYER Dashboard
 *
 * @package clinton-child
 * @author  Clinton
 */
?>

<?php
global $USER_PAYLOAD;
if (!$USER_PAYLOAD['status']){
    return redirect('/lawyer_login');
}

if ($USER_PAYLOAD['data'] -> role_id != 2){
    return redirect('/lawyer_login');
}

get_template_part('template-parts/usernav');
?>
</section>

<section>
    <div class="columns">
        <div class="column is-narrow box is-padded">
            <?php get_template_part('template-parts/lawyer', 'nav'); ?>
        </div>
        <div class="column">
            <?php
            $pagename = explode('-', get_query_var('pagename'));
            if (count($pagename) == 1){
                get_template_part( 'pages/lawyer/' . $pagename[0]);
            } else {
                get_template_part( 'pages/lawyer/' . $pagename[0], $pagename[1]);
            }
            ?>
        </div>
    </div>
</section>

<?php
get_template_part('template-parts/footer');
?>
