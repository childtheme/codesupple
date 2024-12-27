<?php
/*
Plugin Name: Custom Shortcode Before Content
Plugin URI: https://github.com/childtheme/codesupple/tree/shortcode
Description: Display a custom shortcode before site content or above the post title.
Version: 1.0
Author: Dmitrii Chempalov
Author URI: https://github.com/childtheme/codesupple/tree/shortcode
License: GPL2
*/

// Example Shortcode Function
define('MY_SHORTCODE_KEY', 'example_shortcode');
function example_shortcode_function($atts) {
    $atts = shortcode_atts(
        array(
            'name' => 'User', // Default value for name attribute
        ),
        $atts
    );
    return '<div style="padding: 10px; background: #f0f0f0;">Hello, ' . esc_html($atts['name']) . '! This is an example shortcode output.</div>';
}
add_shortcode(MY_SHORTCODE_KEY, 'example_shortcode_function');

// Add the shortcode before the content
function display_custom_shortcode_before_content($content) {
    if (is_single()) { // Ensure this applies only to single posts
        global $post;
        $custom_shortcode = get_post_meta($post->ID, 'custom_shortcode', true); // Get the custom field value
        if (!empty($custom_shortcode)) {
            $shortcode_output = do_shortcode($custom_shortcode); // Process the shortcode
            $content = $shortcode_output . $content; // Add the shortcode output before the content
        }
    }
    return $content;
}
add_filter('the_content', 'display_custom_shortcode_before_content');

// Add the shortcode above the post title
function add_shortcode_before_title() {
    if (is_single()) { // Ensure this applies only to single posts
        global $post;
        $custom_shortcode = get_post_meta($post->ID, 'custom_shortcode', true); // Get the custom field value
        if (!empty($custom_shortcode)) {
            echo do_shortcode($custom_shortcode); // Output the shortcode
        }
    }
}
add_action('wp_head', 'add_shortcode_before_title');

// Enqueue admin scripts to guide users
function custom_shortcode_admin_notice() {
    echo '<div class="notice notice-info is-dismissible">
            <p>To use the Custom Shortcode plugin, add a custom field with the key <strong>custom_shortcode</strong> and your desired shortcode as the value for each post.</p>
         </div>';
}
add_action('admin_notices', 'custom_shortcode_admin_notice');
