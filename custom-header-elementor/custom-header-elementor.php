<?php 
/**
 * Plugin Name: Custom Header for Elementor (Free Version)
 * Description: A plugin to add a custom header using the free version of Elementor.
 * Version: 1.0
 * Author: Dmitrii Chempalov
 * License: GPLv2 or later
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create a custom header area
function custom_header_elementor_free() {
    // Check if the Elementor plugin is active
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    // Get the Elementor header template ID (created in Elementor free version)
    $header_template_id = get_option( 'custom_header_template_id' );

    // If a template ID is set, display the header template
    if ( $header_template_id ) {
        echo '<header class="site-header">';
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $header_template_id );
        echo '</header>';
    }
}
add_action( 'wp_body_open', 'custom_header_elementor_free' );

// Add settings page to select the header template
function custom_header_elementor_free_menu() {
    add_options_page(
        'Custom Header Settings', 
        'Custom Header', 
        'manage_options', 
        'custom-header-elementor-free', 
        'custom_header_elementor_free_settings_page'
    );
}
add_action( 'admin_menu', 'custom_header_elementor_free_menu' );

// Header settings page
function custom_header_elementor_free_settings_page() {
    if ( isset( $_POST['header_template_id'] ) ) {
        update_option( 'custom_header_template_id', intval( $_POST['header_template_id'] ) );
        echo '<div class="updated"><p>Header Template Saved</p></div>';
    }

    $selected_template_id = get_option( 'custom_header_template_id' );
    ?>
    <div class="wrap">
        <h1>Custom Header Settings</h1>
        <form method="post">
            <label for="header_template_id">Select Header Template (Elementor):</label>
            <select name="header_template_id" id="header_template_id">
                <option value="">-- Select Template --</option>
                <?php
                $args = array(
                    'post_type'      => 'elementor_library',
                    'posts_per_page' => -1,
                );
                $templates = get_posts( $args );
                foreach ( $templates as $template ) {
                    $selected = ( $selected_template_id == $template->ID ) ? 'selected' : '';
                    echo '<option value="' . $template->ID . '" ' . $selected . '>' . $template->post_title . '</option>';
                }
                ?>
            </select>
            <br><br>
            <input type="submit" class="button-primary" value="Save Header Template" />
        </form>
    </div>
    <?php
}
