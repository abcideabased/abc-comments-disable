<?php
/*
Plugin Name: ABC - Disable Comments
Plugin URI: https://abcideabased.com/
Description: Used to disable the comments system on the back-end of the site.
Version: 1.0.0
Author: ABC Creative Group
Author URI: https://abcideabased.com/
License: GPLv2 or later
Text Domain: abc-comments-disable
Update URI: https://github.com/abcideabased/abc-comments-disable
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'COMMENTS_DISABLE_VERSION', '1.0.0' );
define( 'COMMENTS_DISABLE__MINIMUM_WP_VERSION', '5.0' );
define( 'COMMENTS_DISABLE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'COMMENTS_DISABLE_DELETE_LIMIT', 100000 );

register_activation_hook( __FILE__, array( 'ABC-Comments-Disable', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'ABC-Comments-Disable', 'plugin_deactivation' ) );

require_once( COMMENTS_DISABLE__PLUGIN_DIR . 'disable.php' );

add_action( 'init', array( 'ABC-Comments-Disable', 'init' ) );



/** Update check */
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/abcideabased/abc-comments-disable',
	__FILE__,
	'abc-comments-disable'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

// Uses GitHub Releases
$myUpdateChecker->getVcsApi()->enableReleaseAssets();
