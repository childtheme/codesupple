<?php
/**
 * Plugin Name: You May Also Like
 * Description: Displays related posts after the content based on categories in a clean grid layout.
 * Version: 1.0
 * Author: Dmitrii Chempalov
 */

// Enqueue styles
function ym_enqueue_styles() {
    wp_enqueue_style('you-may-like-style', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('wp_enqueue_scripts', 'ym_enqueue_styles');

// Display related posts after the content
function ym_display_related_posts($content) {
    if (is_single() && in_the_loop() && is_main_query()) {
        global $post;
        $categories = wp_get_post_categories($post->ID);

        if (!empty($categories)) {
            $args = array(
                'category__in' => $categories,
                'post__not_in' => array($post->ID),
                'posts_per_page' => 2,
                'ignore_sticky_posts' => 1
            );

            $related = new WP_Query($args);

            if ($related->have_posts()) {
                $content .= '<div class="youlike-wrapper">';
                $content .= '<h3 class="youlike-heading">You May Also Like</h3>';
                $content .= '<div class="youlike-container">';

                while ($related->have_posts()) {
                    $related->the_post();
                    $content .= '<a href="' . get_permalink() . '" class="youlike-post">';

                    if (has_post_thumbnail()) {
                        $content .= get_the_post_thumbnail(get_the_ID(), 'medium_large');
                    }

                    $content .= '<div class="youlike-content">';
                    $content .= '<div class="youlike-title">' . get_the_title() . '</div>';
                    $content .= '<div class="youlike-excerpt">' . wp_trim_words(get_the_excerpt(), 15) . '</div>';
                    $content .= '<div class="youlike-meta">';
                    $content .= '<span class="author">by ' . get_the_author() . '</span> &mdash; ';
                    $content .= '<span>' . get_the_date('F j, Y') . '</span>';
                    $content .= '</div></div></a>';
                }

                $content .= '</div></div>';
                wp_reset_postdata();
            }
        }
    }

    return $content;
}
add_filter('the_content', 'ym_display_related_posts');
