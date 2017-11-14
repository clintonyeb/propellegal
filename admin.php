<?php
/**
 * Clinton Child
 *
 * This file serves as the container page for all templates
 *
 * Template Name: ADMIN Dashboard
 *
 * @package clinton-child
 * @author  Clinton
 */
?>

<?php
global $USER_PAYLOAD;

if (!$USER_PAYLOAD['status']){
    return redirect('/login');
}

if ($USER_PAYLOAD['data'] -> role_id != 1){
    return redirect('/login');
}

get_template_part('template-parts/usernav');
?>
</section>

<section>
  <div class="columns">
    <div class="column is-narrow box is-padded is-hidden-touch">
      <?php get_template_part('template-parts/admin', 'nav'); ?>
    </div>
    <div class="column is-narrow is-padded is-hidden-desktop">
      <div id="side-nav" class="sidenav">
        <a class="closebtn" id="close-btn">&times;</a>
        <?php get_template_part('template-parts/admin', 'nav'); ?>
      </div>
    </div>
        <div class="column">
            <?php
            $pagename = explode('-', get_query_var('pagename'));
            if (count($pagename) == 1){
                get_template_part( 'pages/admin/' . $pagename[0]);
            } else {
                get_template_part( 'pages/admin/' . $pagename[0], $pagename[1]);
            }
            ?>
        </div>
    </div>
</section>

<?php
get_template_part('template-parts/footer');
?>
