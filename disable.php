<?php
/**
 * Remove comments feature everywhere and prevent direct access
 *
 * @package ABC
 * @since 1.0.0
 */

// Disable support for comments and trackbacks in post types
function disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ( $post_types as $post_type ) {
		if ( post_type_supports($post_type, 'comments') ) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'disable_comments_post_types_support');

// Close comments on the front-end
function disable_comments_status() {
	return false;
}
add_filter('comments_open', 'disable_comments_status', 20, 2);
add_filter('pings_open', 'disable_comments_status', 20, 2);

// Hide existing comments
function disable_comments_hide_existing_comments( $comments ) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'disable_comments_admin_menu');
// Redirect any user trying to access comments page
function disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ( 'edit-comments.php' === $pagenow ) {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'disable_comments_admin_menu_redirect');
// Remove comments metabox from dashboard
function disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'disable_comments_dashboard');
// Remove comments links from admin bar
function disable_comments_admin_bar() {
	if ( is_admin_bar_showing() ) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'disable_comments_admin_bar');
// Remove from admin bar
function remove_comments_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'remove_comments_admin_bar' );

// Remove comments from At a Glance
add_action( 'do_meta_boxes', 'custom_do_meta_boxes', 99, 2 );

function custom_do_meta_boxes( $screen, $place ) {
	if ( 'dashboard' === $screen && 'normal' === $place ) {
		add_filter( 'wp_count_comments', 'custom_wp_count_comments' );
	}
}

function custom_wp_count_posts( $stats ) {
	static $filter_posts = 0;
	if ( 1 === $filter_posts )
			remove_filter( current_filter(), __FUNCTION__ );

	$filter_posts++;
	return null;
}

function custom_wp_count_comments( $stats ) {
		static $filter_comments = 0;
		if( 1 === $filter_comments )
				remove_filter( current_filter(), __FUNCTION__ );

		$filter_comments++;
		return array( 'total_comments' => 0 );
}

/**
 * Disable comments RSS feed
 */
function disable_comment_feed() {
	wp_die( 'No feed available, please visit our <a href="' . esc_url(home_url( '/' )) . '">homepage</a>!' );
}

// add_action('do_feed', 'disable_comment_feed', 1);
// add_action('do_feed_rdf', 'disable_comment_feed', 1);
// add_action('do_feed_rss', 'disable_comment_feed', 1);
add_action('do_feed_rss2', 'disable_comment_feed', 1);
// add_action('do_feed_atom', 'disable_comment_feed', 1);
add_action('do_feed_rss2_comments', 'disable_comment_feed', 1);
add_action('do_feed_atom_comments', 'disable_comment_feed', 1);
