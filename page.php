<?php
/**
 * Clinton Child
 *
 * This file serves as the container page for all templates
 *
 * Template Name: USER Dashboard
 *
 * @package clinton-child
 * @author  Clinton
 */
?>

<?php
global $USER_PAYLOAD;

if (!$USER_PAYLOAD['status']){
    redirect('/login');
}

if ($USER_PAYLOAD['data'] -> role_id != 3){
    return redirect('/login');
}

get_template_part('template-parts/usernav');
?>
</section>

<section>
    <div class="columns">
        <div class="column is-narrow box is-padded">
            <?php get_template_part('template-parts/user', 'nav'); ?>
        </div>
        <div class="column">
            <?php
            $pagename = explode('-', get_query_var('pagename'));
            if (count($pagename) == 1){
                get_template_part( 'pages/user/' . $pagename[0]);
            } else {
                get_template_part( 'pages/user/' . $pagename[0], $pagename[1]);
            }
            ?>
        </div>
    </div>
</section>

<?php
get_template_part('template-parts/footer');
?>
