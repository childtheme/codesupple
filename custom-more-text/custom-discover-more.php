<?php
/**
 * Plugin Name: Custom Discover More Text
 * Plugin URI:  https://github.com/childtheme/codesupple
 * Description: Adds a section in the Customizer to allow users to replace the "Discover More" text with custom text.
 * Version:     1.0
 * Author:      Dmitrii Chempalov
 * Author URI:  https://github.com/childtheme/codesupple
 * Text Domain: custom-discover-more
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Add a custom section in the Customizer.
function cdm_customize_register( $wp_customize ) {
    // Add a new section.
    $wp_customize->add_section(
        'cdm_extra_section',
        array(
            'title'    => __( 'Extra', 'custom-discover-more' ),
            'priority' => 200,
        )
    );

    // Add a setting for the "Discover More" text.
    $wp_customize->add_setting(
        'cdm_discover_more_text',
        array(
            'default'           => __( 'Discover More', 'custom-discover-more' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    // Add a control for the setting.
    $wp_customize->add_control(
        'cdm_discover_more_text_control',
        array(
            'label'    => __( 'Discover More Text', 'custom-discover-more' ),
            'section'  => 'cdm_extra_section',
            'settings' => 'cdm_discover_more_text',
            'type'     => 'text',
        )
    );
}
add_action( 'customize_register', 'cdm_customize_register' );

// Update the "Discover More" button with the custom text.
if ( ! function_exists( 'csco_discover_more_button' ) ) {
    /**
     * Discover More Button
     */
    function csco_discover_more_button() {
        // Get the custom text from the Customizer or use the default.
        $custom_text = get_theme_mod( 'cdm_discover_more_text', __( 'Discover More', 'custom-discover-more' ) );
        $button_label = sprintf(
            /* translators: %s: Post Title */
            __( '%s: %s', 'custom-discover-more' ),
            $custom_text,
            get_the_title()
        );
        ?>
        <div class="cs-entry__discover-more">
            <a class="cs-button" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( $button_label ); ?>">
                <?php echo esc_html( $custom_text ); ?>
            </a>
        </div>
        <?php
    }
}
