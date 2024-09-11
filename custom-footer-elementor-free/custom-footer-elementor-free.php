<?php
/**
 * Plugin Name: Custom Footer for Elementor (Free Version)
 * Description: A plugin to add a custom footer using the free version of Elementor.
 * Version: 1.0
 * Author: Dmitrii Chempalov
 * Author URI: https://github.com/childtheme/codesupple/
 * License: GPLv2 or later
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create a custom footer area
function custom_footer_elementor_free() {
    // Check if the Elementor plugin is active
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    // Get the Elementor footer template ID (created in Elementor free version)
    $footer_template_id = get_option( 'custom_footer_template_id' );

    // If a template ID is set, display the footer template
    if ( $footer_template_id ) {
        echo '<footer class="site-footer">';
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_template_id );
        echo '</footer>';
    }
}
add_action( 'wp_footer', 'custom_footer_elementor_free' );

// Add settings page to select the footer template
function custom_footer_elementor_free_menu() {
    add_options_page(
        'Custom Footer Settings', 
        'Custom Footer', 
        'manage_options', 
        'custom-footer-elementor-free', 
        'custom_footer_elementor_free_settings_page'
    );
}
add_action( 'admin_menu', 'custom_footer_elementor_free_menu' );

// Footer settings page
function custom_footer_elementor_free_settings_page() {
    if ( isset( $_POST['footer_template_id'] ) ) {
        update_option( 'custom_footer_template_id', intval( $_POST['footer_template_id'] ) );
        echo '<div class="updated"><p>Footer Template Saved</p></div>';
    }

    $selected_template_id = get_option( 'custom_footer_template_id' );
    ?>
    <div class="wrap">
        <h1>Custom Footer Settings</h1>
        <form method="post">
            <label for="footer_template_id">Select Footer Template (Elementor):</label>
            <select name="footer_template_id" id="footer_template_id">
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
            <input type="submit" class="button-primary" value="Save Footer Template" />
        </form>
    </div>
    <?php
}

// Hook in the plugin to display the custom footer
add_filter( 'template_include', 'custom_footer_elementor_free_template_override' );

function custom_footer_elementor_free_template_override( $template ) {
    if ( is_page_template( 'custom-footer-template.php' ) ) {
        $new_template = locate_template( array( 'custom-footer-template.php' ) );
        if ( '' != $new_template ) {
            return $new_template ;
        }
    }
    return $template;
}
