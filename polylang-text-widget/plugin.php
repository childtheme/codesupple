<?php
/*
Plugin Name: Polylang Text Widget
Description: A widget that displays text for English or Arabic based on the selected language using Polylang.
Version: 1.0
Author: Dmitrii Chempalov
*/

require_once plugin_dir_path(__FILE__) . 'settings-page.php';
require_once plugin_dir_path(__FILE__) . 'widget.php';

/**
 * Enqueue admin styles for the settings page.
 */
function polylang_text_widget_admin_styles() {
    wp_enqueue_style('polylang-text-widget-admin', plugins_url('admin-style.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'polylang_text_widget_admin_styles');
