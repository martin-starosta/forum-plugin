<?php
/**
 * Plugin Name: Forum Plugin for Wordpress
 * Plugin URI: https://mayorsoft.eu
 * Description: Plugin adds forum features to Wordpress site.
 * Version: 1.0
 * Author: Martin Starosta
 * Author URI: https://mayorsoft.eu
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/fp.php';

/**
 * Run Forum plugin code
 */
function run_forum() {
	$plugin = new Forum();
}

run_forum();

