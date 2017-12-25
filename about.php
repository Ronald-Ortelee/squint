<?php
/**
* Template Name: About Squint Template
* Description: Template used for the about page
*/


//* Add Custom Body Class
add_filter( 'body_class', 'childthemeprefix_about_body_class' );
function childthemeprefix_about_body_class( $classes ) {
	$classes[] = 'about-page';
	return $classes;
}

add_action( 'genesis_meta', 'digital_front_page_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function digital_front_page_genesis_meta() {



		//* Enqueue scripts
		add_action( 'wp_enqueue_scripts', 'digital_enqueue_digital_script' );
		function digital_enqueue_digital_script() {

			wp_register_style( 'digitalIE9', get_stylesheet_directory_uri() . '/style-ie9.css', array(), CHILD_THEME_VERSION );
			wp_style_add_data( 'digitalIE9', 'conditional', 'IE 9' );
			wp_enqueue_style( 'digitalIE9' );

			wp_enqueue_script( 'digital-front-script', get_stylesheet_directory_uri() . '/js/front-page.js', array( 'jquery' ), CHILD_THEME_VERSION );
			wp_enqueue_script( 'localScroll', get_stylesheet_directory_uri() . '/js/jquery.localScroll.min.js', array( 'scrollTo' ), '1.2.8b', true );
			wp_enqueue_script( 'scrollTo', get_stylesheet_directory_uri() . '/js/jquery.scrollTo.min.js', array( 'jquery' ), '1.4.5-beta', true );

			wp_enqueue_style( 'digital-front-styles', get_stylesheet_directory_uri() . '/style-front.css', array(), CHILD_THEME_VERSION );

		}

		//* Enqueue scripts for backstretch
		add_action( 'wp_enqueue_scripts', 'digital_front_page_enqueue_scripts' );
		function digital_front_page_enqueue_scripts() {

			$image = get_option( 'digital-front-image', sprintf( '%s/images/squint.png', get_stylesheet_directory_uri() ) );

			//* Load scripts only if custom backstretch image is being used
			if ( ! empty( $image ) && is_active_sidebar( 'about-page-1' ) ) {

				//* Enqueue Backstretch scripts
				wp_enqueue_script( 'digital-backstretch', get_bloginfo( 'stylesheet_directory' ) . '/js/backstretch.js', array( 'jquery' ), '1.0.0' );
				wp_enqueue_script( 'digital-backstretch-set', get_bloginfo('stylesheet_directory').'/js/backstretch-set.js' , array( 'jquery', 'digital-backstretch' ), '1.0.0' );

				wp_localize_script( 'digital-backstretch-set', 'BackStretchImg', array( 'src' => str_replace( 'http:', '', $image ) ) );

			}

		}}




add_filter( 'genesis_attr_site-inner', 'sk_attributes_site_inner' );
/**
 * Add attributes for site-inner element.
 *
 * @since 2.0.0
 *
 * @param array $attributes Existing attributes.
 *
 * @return array Amended attributes.
 */
function sk_attributes_site_inner( $attributes ) {
	$attributes['role']     = 'main';
	$attributes['itemprop'] = 'mainContentOfPage';
	return $attributes;
}

// //* Remove .site-inner
			add_filter( 'genesis_markup_site-inner', '__return_null' );
			add_filter( 'genesis_markup_content-sidebar-wrap_output', '__return_false' );
			add_filter( 'genesis_markup_content', '__return_null' );

genesis_do_post_content();
get_header();

// Content
genesis_widget_area( 'about-page-1', array(
	'before' => '<div id="about-page-1" class="about-page-1"><div class="widget-area fadeup-effect"><div class="wrap">',
	'after'  => '</div></div></div>',
) );

genesis_widget_area( 'about-page-2', array(
	'before' => '<div id="about-page-2" class="front-page-2 "><div class="widget-area fadeup-effect"><div class="wrap">',
	'after'  => '</div></div></div>',
) );


// Display Footer
get_footer();
