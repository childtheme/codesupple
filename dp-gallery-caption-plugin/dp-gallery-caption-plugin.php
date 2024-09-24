<?php
/**
 * Plugin Name: DP Gallery with Caption
 * Description: A plugin to create a gallery with captions at the bottom and allow columns selection via shortcode.
 * Version: 1.0
 * Author: Dmitrii Chempalov
 * Author URI: https://github.com/childtheme/codesupple/
 */

function dp_gallery_with_caption_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'columns' => '3',
        'ids'     => '',
    ), $atts, 'dp_gallery' );

    $image_ids = explode( ',', $atts['ids'] );
    $columns_class = 'columns-' . intval($atts['columns']);

    if ( empty( $image_ids ) ) {
        return 'No images provided.';
    }

    $output = '<div class="dp-gallery ' . esc_attr($columns_class) . '">';

    foreach ( $image_ids as $id ) {
        $image_url = wp_get_attachment_image_src( $id, 'large' );
        $image_caption = wp_get_attachment_caption( $id );

        if ( $image_url ) {
            $output .= '<figure class="wp-block-image">';
            $output .= '<img src="' . esc_url( $image_url[0] ) . '" alt="' . esc_attr( $image_caption ) . '">';
            if ( $image_caption ) {
                $output .= '<figcaption><div class="caption-content">' . esc_html( $image_caption ) . '</div></figcaption>';
            }
            $output .= '</figure>';
        }
    }

    $output .= '</div>';

    return $output;
}

add_shortcode( 'dp_gallery', 'dp_gallery_with_caption_shortcode' );

function dp_gallery_enqueue_styles() {
    wp_enqueue_style( 'dp-gallery-caption-style', plugin_dir_url( __FILE__ ) . 'css/dp-gallery.css' );
}
add_action( 'wp_enqueue_scripts', 'dp_gallery_enqueue_styles' );
