<?php
/*
Plugin Name: Swyft Custom Logo URL Plugin
Description: Adds a custom logo URL option to the WordPress Customizer for the Swyft theme, allowing users to set a custom link for the logo.
Version: 1.0
Author: Dmitrii Chempalov
License: GPL2
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add Customizer setting for custom logo URL
function swyft_custom_logo_url_customizer( $wp_customize ) {
    // Add a section in the Customizer
    $wp_customize->add_section( 'swyft_logo_settings', array(
        'title'    => __( 'Logo Settings', 'swyft-custom-logo-url-plugin' ),
        'priority' => 30,
    ) );

    // Add the setting for the custom logo URL
    $wp_customize->add_setting( 'custom_logo_url', array(
        'default'           => home_url( '/' ),
        'sanitize_callback' => 'esc_url_raw',
    ) );

    // Add the control for the custom logo URL
    $wp_customize->add_control( 'custom_logo_url_control', array(
        'label'    => __( 'Custom Logo URL', 'swyft-custom-logo-url-plugin' ),
        'section'  => 'swyft_logo_settings',
        'settings' => 'custom_logo_url',
        'type'     => 'url',
    ) );
}
add_action( 'customize_register', 'swyft_custom_logo_url_customizer' );

// Update logo link to use the custom URL
function swyft_replace_logo_url( $url ) {
    return esc_url( get_theme_mod( 'custom_logo_url', home_url( '/' ) ) );
}
add_filter( 'theme_logo_url', 'swyft_replace_logo_url' );

