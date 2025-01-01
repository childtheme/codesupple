<?php
/**
 * Plugin Name: Page Excerpt Display
 * Description: A plugin to enable and display excerpts for pages.
 * Version: 1.0
 * Author: Dmitrii Chempalov
 * License: GPL2
 */

// Add excerpt support for pages
function add_excerpt_support_for_pages() {
    add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'add_excerpt_support_for_pages' );

// Display the excerpt on the page template
function display_page_excerpt($content) {
    if ( is_page() && has_excerpt() ) {
        $excerpt = get_the_excerpt();
        $excerpt_html = '<div class="page-excerpt">' . $excerpt . '</div>';
        $content = $excerpt_html . $content;  // Display excerpt before the content
    }
    return $content;
}
add_filter( 'the_content', 'display_page_excerpt', 5 );

// Enqueue plugin styles
function page_excerpt_plugin_styles() {
    wp_enqueue_style( 'page-excerpt-style', plugin_dir_url( __FILE__ ) . 'style.css' );
}
add_action( 'wp_enqueue_scripts', 'page_excerpt_plugin_styles' );
