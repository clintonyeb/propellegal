<?php
global $USER_PAYLOAD;
$logged_in = $USER_PAYLOAD['status'];

do_action( 'genesis_doctype' );
?>
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php

do_action( 'genesis_title' );
do_action( 'genesis_meta' );
do_action('get_header');
wp_head(); // We need this for plugins.
?>
    </head>
    <?php
    genesis_markup( array(
        'open'   => '<body %s>',
        'context' => 'body',
    ) );

    // Add landing body class to the head.
    add_filter( 'body_class', 'executive_add_landing_body_class' );
    function executive_add_landing_body_class( $classes ) {

        $classes[] = 'executive-pro-landing';
        return $classes;

    }

    // Remove Skip Links.
    remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );

    // Dequeue Skip Links Script.
    add_action( 'wp_enqueue_scripts', 'executive_dequeue_skip_links' );
    function executive_dequeue_skip_links() {

        wp_dequeue_script( 'skip-links' );

    }

    // Force full width content layout.
    add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

    // Remove site header elements.
    remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
    remove_action( 'genesis_header', 'genesis_do_header' );
    remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

    // Remove navigation.
    remove_theme_support( 'genesis-menus' );

    // Remove breadcrumbs.
    remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );


    // Remove site footer widgets.
    remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

    // Remove site footer elements.
    remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
    remove_action( 'genesis_footer', 'genesis_do_footer' );
    remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

    // Run the Genesis loop.
    //  genesis();

    ?>
