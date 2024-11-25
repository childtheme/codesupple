Adds a custom logo URL option to the WordPress Customizer for the Swyft theme, allowing users to set a custom link for the logo.
Instruction:
1. Activate the Child theme and place this file theme-tags.php on the Child theme > inc folder.
2. Place this code on the bottom Child theme >function.php:
3. Set link on the Appearance > Customizer > Logo Settings

<code>
function swyft_custom_logo_url_customizer( $wp_customize ) {
    // Add a section in the Customizer
    $wp_customize->add_section( 'swyft_logo_settings', array(
        'title'    => __( 'Logo Settings', 'swyft' ),
        'priority' => 30,
    ) );
    // Add the setting for the custom logo URL
    $wp_customize->add_setting( 'custom_logo_url', array(
        'default'           => home_url( '/' ),
        'sanitize_callback' => 'esc_url_raw',
    ) );
    // Add the control for the custom logo URL
    $wp_customize->add_control( 'custom_logo_url_control', array(
        'label'    => __( 'Custom Logo URL', 'swyft' ),
        'section'  => 'swyft_logo_settings',
        'settings' => 'custom_logo_url',
        'type'     => 'url',
    ) );
}
add_action( 'customize_register', 'swyft_custom_logo_url_customizer' );
</code>

<br>Revision theme code<br>

<code>
function revision_custom_logo_url_customizer( $wp_customize ) {
    // Add a section in the Customizer
    $wp_customize->add_section( 'revision_logo_settings', array(
        'title'    => __( 'Logo Settings', 'revision' ),
        'priority' => 30,
    ) );
    // Add the setting for the custom logo URL
    $wp_customize->add_setting( 'custom_logo_url', array(
        'default'           => home_url( '/' ),
        'sanitize_callback' => 'esc_url_raw',
    ) );
    // Add the control for the custom logo URL
    $wp_customize->add_control( 'custom_logo_url_control', array(
        'label'    => __( 'Custom Logo URL', 'revision' ),
        'section'  => 'revision_logo_settings',
        'settings' => 'custom_logo_url',
        'type'     => 'url',
    ) );
}
add_action( 'customize_register', 'revision_custom_logo_url_customizer' );

</code>
