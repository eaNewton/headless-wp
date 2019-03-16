<?php
/**
 * Table of Contents
 *
 * 1. Require statements
 * 2. Action addition/removal
 * 3. Enqueue resources
 */

/**
 * 1. Require statements
 */
require_once get_stylesheet_directory() . '/inc/post_types/movies.php';

/**
 * 2. Action addition/removal
 */
add_action( 'init', 'create_movies' );
add_action( 'wp_enqueue_scripts', 'wasabi_enqueue_styles' );

/**
 * 3. Enqueue resources
 */
function wasabi_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
