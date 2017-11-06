<?php
global $USER_PAYLOAD;
$logged_in = $USER_PAYLOAD['status'];

do_action( 'genesis_doctype' );
do_action( 'genesis_title' );
do_action( 'genesis_meta' );
do_action('get_header');

wp_head(); // We need this for plugins.
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
                             <meta name="google-site-verification" content="6rEdp4MOY1B5_eG_iUqBwLkFJSquIH59sRWpkNJOfj4" />
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

// database requests => my stuff starts here

$notif_count = getUserNotifCount();
$user = $USER_PAYLOAD['data'];
?>
<section class="" id="hero">
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="navbar-brand is-hidden-desktop" id="brand-mob" >
	    <a class="navbar-item" href="/">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-text.svg" alt="logo brand" class="is-page-brand">
	    </a>

	    <span class="navbar-burger burger" data-target="main-menu">
	        <span></span>
	        <span></span>
	        <span></span>
	    </span>
        </div>

        <div class="navbar-menu is-page-brand" id="main-menu">

            <div class="navbar-start">
                <a class="navbar-item has-text-white" data-scroll="general">
                    SERVICES
                </a>
                <a class="navbar-item has-text-white" href="/how-it-works">
                    HOW IT WORKS
                </a>
                <a class="navbar-item has-text-white" href="/pricing">
                    PRICING
                </a>
            </div>

            <div class="navbar-center is-hidden-touch">
                <div id="brand">
                    <a href="/">
                           <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-text.svg" alt="logo brand">
                    </a>
                </div>
            </div>

            <div class="navbar-end">
                <div class="navbar-item" style="padding: .5rem;">
                    <div class="button is-dark menu-icon">
                        <a class="icon is-small has-text-white <?php echo ($notif_count > 0 ? 'badge' : '') ?>" data-noti-count="<?php echo $notif_count ?>" href="/<?php echo getRolePath(($user -> role_id)) ?>/notifications">
                            <i class="fa fa-bell"></i>
                        </a>
                    </div>
                </div>
                <span class="navbar-item">
                    <a class="button is-dark menu-icon" href="/user">
                        <span class="icon is-small">
                            <i class="fa fa-user"></i>
                        </span>
                    </a>
                </span>
            </div>
        </div>
    </nav>
</section>
