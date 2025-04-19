
<?php
/**
 * Plugin Name: Custom Footer with 4 Widget Columns
 * Description: Adds a custom footer with 4 widget areas. No page builders required.
 * Version: 1.1
 * Author: Dmitrii Chempalov
 * License: GPL2 or later
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Register 4 footer widget areas
function cf_register_footer_widgets() {
    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            'name'          => __( "Footer Column $i", 'cf-footer' ),
            'id'            => "cf-footer-col-$i",
            'before_widget' => '<div class="footer-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>',
        ) );
    }
}
add_action( 'widgets_init', 'cf_register_footer_widgets' );

// Render the footer widgets
function cf_render_footer_widgets() {
    echo '<div class="cf-footer-widgets"><div class="cf-footer-row">';
    for ( $i = 1; $i <= 4; $i++ ) {
        echo '<div class="cf-footer-col">';
        if ( is_active_sidebar( "cf-footer-col-$i" ) ) {
            dynamic_sidebar( "cf-footer-col-$i" );
        }
        echo '</div>';
    }
    echo '</div></div>';
}

// Automatically output custom footer before </body>
add_action('wp_footer', 'cf_render_footer_widgets', 20);

// Add custom styles
function cf_footer_custom_styles() {
    echo '<style>
        .cf-footer-widgets {
            padding: 40px;
            background: #f9f9f9;
        }
        .cf-footer-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .cf-footer-col {
            flex: 1;
            min-width: 200px;
        }
        .footer-widget h4 {
            margin-bottom: 15px;
        }
        [data-scheme] {
            color: #000000;
        }
        .cf-footer-widgets a {
            color: #000000;
        }
    </style>';
}
add_action( 'wp_head', 'cf_footer_custom_styles' );
