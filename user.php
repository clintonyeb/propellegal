<?php
/**
 * Clinton Child
 *
 * This file serves as the container page for all templates
 *
 * Template Name: User Pages
 *
 * @package clinton-child
 * @author  Clinton
 */
?>

<?php
global $USER_PAYLOAD;

get_template_part('template-parts/nav');
?>

<?php
$pagename = explode('-', get_query_var('pagename'));
if (count($pagename) == 1){
    get_template_part( 'pages/' . $pagename[0]);
} else {
    get_template_part( 'pages/' . $pagename[0], $pagename[1]);
}
?>

<?php
get_template_part('template-parts/footer');
?>
