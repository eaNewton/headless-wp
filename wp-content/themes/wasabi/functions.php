<?php
/**
 * Table of Contents
 *
 * 1. Action addition/removal
 * 2. Enqueue Resources
 */

/**
 * 1. Action addition/removal
 */
add_action( 'wp_enqueue_scripts', 'wasabi_enqueue_styles' );

/**
 * 2. Enqueue Resources
 */
function wasabi_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
