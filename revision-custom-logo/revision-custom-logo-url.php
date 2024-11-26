<?php
/*
Plugin Name: Custom Logo URL Plugin for Revision WordPress theme
Description: Overrides the theme's logo link to use a custom URL set in the Customizer.
Version: 1.0
Author: Dmitrii Chempalov
License: GPL2
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add Customizer setting for custom logo URL.
function custom_logo_url_customizer( $wp_customize ) {
    // Add a section in the Customizer.
    $wp_customize->add_section( 'custom_logo_settings', array(
        'title'    => __( 'Logo Settings', 'custom-logo-url-plugin' ),
        'priority' => 30,
    ) );

    // Add the setting for the custom logo URL.
    $wp_customize->add_setting( 'custom_logo_url', array(
        'default'           => home_url( '/' ),
        'sanitize_callback' => 'esc_url_raw',
    ) );

    // Add the control for the custom logo URL.
    $wp_customize->add_control( 'custom_logo_url_control', array(
        'label'    => __( 'Custom Logo URL', 'custom-logo-url-plugin' ),
        'section'  => 'custom_logo_settings',
        'settings' => 'custom_logo_url',
        'type'     => 'url',
    ) );
}
add_action( 'customize_register', 'custom_logo_url_customizer' );

// Replace the theme's logo link with the custom URL.
function custom_logo_url_override() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const logoLinks = document.querySelectorAll('.cs-header__logo');
            const customLogoUrl = '<?php echo esc_js( get_theme_mod( 'custom_logo_url', home_url( '/' ) ) ); ?>';

            if (customLogoUrl && logoLinks.length) {
                logoLinks.forEach(link => {
                    link.setAttribute('href', customLogoUrl);
                });
            }
        });
    </script>
    <?php
}
add_action( 'wp_footer', 'custom_logo_url_override' );
