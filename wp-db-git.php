<?php

/*
Plugin Name: WordpressGitDB
Plugin URI: No yet
Description: Compress DB control db
Version: 0.1
Author: Luis David
Author URI:
License: GPLv2
*/
if ( file_exists( $composer_autoload = __DIR__ . '/vendor/autoload.php' ) /* check in self */
    || file_exists( $composer_autoload = WP_CONTENT_DIR.'/vendor/autoload.php') /* check in wp-content */
    || file_exists( $composer_autoload = plugin_dir_path( __FILE__ ).'vendor/autoload.php') /* check in plugin directory */
    || file_exists( $composer_autoload = get_stylesheet_directory().'/vendor/autoload.php') /* check in child theme */
    || file_exists( $composer_autoload = get_template_directory().'/vendor/autoload.php') /* check in parent theme */
) {
    require_once $composer_autoload;
}


use WordpressDB\wpGitDB;

new wpGitDB();