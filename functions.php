<?php
/**
 * Digital Pro.
 *
 * This file adds the functions to the Digital Pro Theme.
 *
 * @package Digital
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/digital/
 */

// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
add_action( 'after_setup_theme', 'digital_localization_setup' );
function digital_localization_setup(){
	load_child_theme_textdomain( 'digital-pro', get_stylesheet_directory() . '/languages' );
}

// Add the theme's helper functions.
include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

// Add Image upload and Color select to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Add the theme's required WooCommerce functions.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

// Add the custom CSS to the theme's WooCommerce stylesheet.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

// Include notice to install Genesis Connect for WooCommerce.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Digital Pro' );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/digital/' );
define( 'CHILD_THEME_VERSION', '1.1.2' );

// Enqueue scripts and styles.
add_action( 'wp_enqueue_scripts', 'digital_scripts_styles' );
function digital_scripts_styles() {

	// wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lora:400,400italic,700,700italic|Poppins:400,500,600,700', array(), CHILD_THEME_VERSION );

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Droid Serif:regular,italic,700,700italic', array(), CHILD_THEME_VERSION );

	wp_enqueue_style( 'ionicons', '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css', array(), CHILD_THEME_VERSION );

	wp_enqueue_script( 'digital-global-scripts', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), CHILD_THEME_VERSION, true );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'digital-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menus' . $suffix . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script(
		'digital-responsive-menu',
		'genesis_responsive_menu',
		digital_responsive_menu_settings()
	);

}

// Define our responsive menu settings.
function digital_responsive_menu_settings() {

	$settings = array(
		'mainMenu'         => __( 'Menu', 'digital-pro' ),
		'menuIconClass'    => 'ionicons-before ion-ios-drag',
		'subMenu'          => __( 'Submenu', 'digital-pro' ),
		'subMenuIconClass' => 'ionicons-before ion-ios-arrow-down',
		'menuClasses'      => array(
			'others'  => array(
				'.nav-primary',
			),
		),
	);

	return $settings;

}

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

// Add accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add screen reader class to archive description.
add_filter( 'genesis_attr_author-archive-description', 'genesis_attributes_screen_reader_class' );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 140,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Rename primary and secondary navigation menus.
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Header Menu', 'digital-pro' ), 'secondary' => __( 'Footer Menu', 'digital-pro' ) ) );

// Remove output of primary navigation right extras
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

// Remove navigation meta box.
add_action( 'genesis_theme_settings_metaboxes', 'digital_remove_genesis_metaboxes' );
function digital_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );
}

// Remove header right widget area.
unregister_sidebar( 'header-right' );

// Add image sizes.
add_image_size( 'front-page-featured', 1000, 700, TRUE );

// Reposition post image.
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 4 );

// Reposition primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Reposition secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 12 );

// Reduce secondary navigation menu to one level depth.
add_filter( 'wp_nav_menu_args', 'digital_secondary_menu_args' );
function digital_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

// Remove skip link for primary navigation.
add_filter( 'genesis_skip_links_output', 'digital_skip_links_output' );
function digital_skip_links_output( $links ) {

	if ( isset( $links['genesis-nav-primary'] ) ) {
		unset( $links['genesis-nav-primary'] );
	}

	return $links;

}

// Remove seondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Remove site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Reposition entry meta in entry header.
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 8 );

// Customize entry meta in the entry header.
add_filter( 'genesis_post_info', 'digital_entry_meta_header' );
function digital_entry_meta_header( $post_info ) {

	$post_info = '[post_author_posts_link] / [post_date] [post_edit]';

	return $post_info;

}

// Customize the content limit more markup.
add_filter( 'get_the_content_limit', 'digital_content_limit_read_more_markup', 10, 3 );
function digital_content_limit_read_more_markup( $output, $content, $link ) {

	$output = sprintf( '<p>%s &#x02026;</p><p class="more-link-wrap">%s</p>', $content, str_replace( '&#x02026;', '', $link ) );

	return $output;

}

// Customize author box title.
add_filter( 'genesis_author_box_title', 'digital_author_box_title' );
function digital_author_box_title() {
	return '<span itemprop="name">' . get_the_author() . '</span>';
}

// Modify size of the Gravatar in the author box.
add_filter( 'genesis_author_box_gravatar_size', 'digital_author_box_gravatar' );
function digital_author_box_gravatar( $size ) {
	return 160;
}

// Modify size of the Gravatar in the entry comments.
add_filter( 'genesis_comment_list_args', 'digital_comments_gravatar' );
function digital_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}

// Remove entry meta in the entry footer on category pages.
add_action( 'genesis_before_entry', 'digital_remove_entry_footer' );
function digital_remove_entry_footer() {

	if ( is_single() ) {
		return;
	}

	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

}

// Setup widget counts.
function digital_count_widgets( $id ) {

	global $sidebars_widgets;

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

// Flexible widget classes.
function digital_widget_area_class( $id ) {

	$count = digital_count_widgets( $id );

	$class = '';

	if ( $count == 1 ) {
		$class .= ' widget-full';
	} elseif ( $count % 3 == 1 ) {
		$class .= ' widget-thirds';
	} elseif ( $count % 4 == 1 ) {
		$class .= ' widget-fourths';
	} elseif ( $count % 2 == 0 ) {
		$class .= ' widget-halves uneven';
	} else {
		$class .= ' widget-halves even';
	}

	return $class;

}

// Flexible widget classes.
function digital_halves_widget_area_class( $id ) {

	$count = digital_count_widgets( $id );

	$class = '';

	if ( $count == 1 ) {
		$class .= ' widget-full';
	} elseif ( $count % 2 == 0 ) {
		$class .= ' widget-halves';
	} else {
		$class .= ' widget-halves uneven';
	}

	return $class;

}

// Add support for 3-column footer widget.
add_theme_support( 'genesis-footer-widgets', 3 );

// Register widget areas.
genesis_register_sidebar( array(
	'id'          => 'front-page-1',
	'name'        => __( 'Front Page 1', 'digital-pro' ),
	'description' => __( 'This is the 1st section on the front page.', 'digital-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-2',
	'name'        => __( 'Front Page 2', 'digital-pro' ),
	'description' => __( 'This is the 2nd section on the front page.', 'digital-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-3',
	'name'        => __( 'Front Page 3', 'digital-pro' ),
	'description' => __( 'This is the 3rd section on the front page.', 'digital-pro' ),
) );



// Custom code starts here


//* Display subtitle to single post, only if the plugin WPSubtitle is active.
add_action( 'genesis_entry_header', 'yiv_do_post_subtitle', 11 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
function yiv_do_post_subtitle() {
    
    if( is_singular() && function_exists('the_subtitle') ) {
      echo '<p class="subtitle">';
      the_subtitle();
      echo '</p>';
    }
    
}

//* Add subtitle support to custom post type 'Portfolio'.
function my_wp_subtitle_page_part_support() {
    add_post_type_support( 'portfolio', 'wps_subtitle' );
}
add_action( 'init', 'my_wp_subtitle_page_part_support' );


//* Add subtitle support to custom post type 'Portfolio'.
function my_wp_porto_gallery() {
    add_post_type_support( 'portfolio', 'porto_gallery' );
}
add_action( 'init', 'my_wp_porto_gallery' );



/** Genesis Previous/Next Post Post Navigation */
function custom_prev_next_post_nav() {
  // Only run on single portfolio items
  if( ! is_singular( 'portfolio' ) )
	return;
	echo '<div id="prev-next">';
	previous_post_link( '<div class="prev-link"> %link</div>', '<span>&larr;</span>%title' );
	next_post_link( '<div class="next-link"> %link</div>', '%title<span>&rarr;</span>' );
	echo '</div><!-- .prev-next-navigation -->';
}
add_action( 'genesis_after_entry', 'custom_prev_next_post_nav' );
function wpb_prev_next_post_nav_cpt() {
	if ( ! is_singular( array( 'portfolio', 'post' ) ) ) //add your CPT name to the array
		return;
	genesis_markup( array(
		'html5'   => '<div %s>',
		'xhtml'   => '<div class="navigation">',
		'context' => 'adjacent-entry-pagination',
	) );
	echo '<div class="pagination-previous alignleft">';
	previous_post_link( '<div class="prev-link"> %link</div>', '<span>&larr;</span>%title' );
	echo '</div>';
	echo '<div class="pagination-next alignright">';
	next_post_link( '<div class="next-link"> %link</div>', '%title<span>&rarr;</span>' );
	echo '</div>';
	echo '</div>';
}



/** Remove entry meta */
add_filter( 'genesis_post_info', 'remove_cpt_post_info' );
function remove_cpt_post_info($post_info) {
if ( is_singular('portfolio') || is_post_type_archive('portfolio') ) :
	$post_info = '[post_comments] [post_edit]';
	return $post_info;
	endif;
}


function portfolio_add_meta_box() {
//this will add the metabox for the portfolio post type
$screens = array( 'portfolio' );

foreach ( $screens as $screen ) {

    add_meta_box(
        'portfolio_sectionid',
        __( 'Envira gallery', 'portfolio_textdomain' ),
        'portfolio_meta_box_callback',
        $screen
    );
 }
}
add_action( 'add_meta_boxes', 'portfolio_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function portfolio_meta_box_callback( $post ) {

// Add a nonce field so we can check for it later.
wp_nonce_field( 'portfolio_save_meta_box_data', 'portfolio_meta_box_nonce' );

/*
 * Use get_post_meta() to retrieve an existing value
 * from the database and use the value for the form.
 */
$value = get_post_meta( $post->ID, '_portogal_meta', true );

echo '<label for="portfolio_new_field">';
_e( 'Gallery shortcode', 'portfolio_textdomain' );
echo '</label> ';
echo '<input type="text" id="portfolio_new_field" name="portfolio_new_field" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
 function portfolio_save_meta_box_data( $post_id ) {

 if ( ! isset( $_POST['portfolio_meta_box_nonce'] ) ) {
    return;
 }

 if ( ! wp_verify_nonce( $_POST['portfolio_meta_box_nonce'], 'portfolio_save_meta_box_data' ) ) {
    return;
 }

 if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
 }

 // Check the user's permissions.
 if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

 } else {

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
 }

 if ( ! isset( $_POST['portfolio_new_field'] ) ) {
    return;
 }

 $my_data = sanitize_text_field( $_POST['portfolio_new_field'] );

 update_post_meta( $post_id, '_portogal_meta', $my_data );
}
add_action( 'save_post', 'portfolio_save_meta_box_data' );



// Register widget areas

genesis_register_sidebar( array(
	'id'            => 'porto-header',
	'name'          => __( 'Portfolio header', 'digital' ),
	'description'   => __( 'The very first content visitors see', 'themename' ),
));

genesis_register_sidebar( array(
	'id'            => 'about-page-1',
	'name'          => __( 'About page 1', 'digital' ),
	'description'   => __( 'This is the homepage featured section', 'themename' ),
));

genesis_register_sidebar( array(
	'id'          => 'about-page-2',
	'name'        => __( 'About Page 2', 'digital' ),
	'description' => __( 'This is the home strap text section.', 'themename' ),
));



add_action( 'genesis_after_header', 'mp_cta_genesis' );

function mp_cta_genesis() {
if ( is_page( 'porto' ) ) {		
	genesis_widget_area( 'porto-header', array(
			'before' => '<div id="cta"><div class="wrap">',
			'after' => '</div></div>',
		) );

}}

//* Remove the site footer
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );






add_shortcode( 'portfolio_terms', 'custom_portfolio_terms_shortcode' );
/**
 * Produces the linked post taxonomy terms list.
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is 'Tagged With: '),
 *   sep (separator string between tags, default is ', '),
 *    taxonomy (name of the taxonomy, default is 'category').
 *
 * Output passes through `genesis_post_terms_shortcode` filter before returning.
 *
 * @since 1.6.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Output for `post_terms` shortcode, or empty string on failure to retrieve terms.
 */
function custom_portfolio_terms_shortcode( $atts ) {

	$defaults = array(
		'after'    => '',
		'before'   => __( 'Categorie: ', 'genesis' ),
		'sep'      => ' ',
		'taxonomy' => 'category',
	);

	/**
	 * Post terms shortcode defaults.
	 *
	 * Allows the default args in the post terms shortcode function to be filtered.
	 *
	 * @since 2.0.0
	 *
	 * @param array $defaults The default args array.
	 */
	$defaults = apply_filters( 'genesis_post_terms_shortcode_defaults', $defaults );

	$atts = shortcode_atts( $defaults, $atts, 'post_terms' );

	$terms = get_the_term_list( get_the_ID(), $atts['taxonomy'], $atts['before'], trim( $atts['sep'] ) . ' ', $atts['after'] );

	if ( is_wp_error( $terms ) )
			return '';

	if ( empty( $terms ) )
			return '';

	if ( genesis_html5() )
		switch ( $atts['taxonomy'] ) {
			case 'portfolio_tag':
				$output = sprintf( '<span %s>', genesis_attr( 'entry-tags' ) ) . $terms . '</span>';
				break;

			default:
				$output = sprintf( '<span %s>', genesis_attr( 'entry-categories' ) ) . $terms . '</span>';
				break;
		}
	else
		$output = '<span class="terms">' . $terms . '</span>';

	return apply_filters( 'genesis_post_terms_shortcode', $output, $terms, $atts );

}

// Show Portfolio Type hyperlinked terms (categories and tags)
add_filter( 'genesis_post_meta', 'sk_post_meta_filter' );
function sk_post_meta_filter( $post_meta ) {

	if ( 'portfolio' == get_post_type() ) {
		$post_meta = '[portfolio_terms taxonomy="portfolio_category"]<br>[portfolio_terms taxonomy="portfolio_tag" before="Tags: "]';
	}

	return $post_meta;

}



/* Unset image-sizes */
function remove_default_image_sizes( $sizes ) {
  
  /* Default WordPress */
  unset( $sizes[ 'thumbnail' ]);       // Remove Thumbnail (150 x 150 hard cropped)
  unset( $sizes[ 'medium' ]);          // Remove Medium resolution (300 x 300 max height 300px)
  unset( $sizes[ 'medium_large' ]);    // Remove Medium Large (added in WP 4.4) resolution (768 x 0 infinite height)
  unset( $sizes[ 'large' ]);           // Remove Large resolution (1024 x 1024 max height 1024px)
  
  return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'remove_default_image_sizes' );


//* Add image sizes
add_image_size( 'portfolio-featured', 1200, 741, TRUE );
