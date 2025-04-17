<?php
/*
Plugin Name: Same Category Posts Widget
Description: Displays a list of posts from the same category in the sidebar on single post pages.
Version: 1.0
Author: Dmitrii Chempalov
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Same_Category_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'same_category_posts_widget',
            __('📂 Same Category Posts', 'text_domain'),
            array('description' => __('Displays posts from the same category on single posts.', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        if (!is_single()) return;

        global $post;
        $categories = wp_get_post_categories($post->ID);

        if (empty($categories)) return;

        $query_args = array(
            'category__in' => $categories,
            'post__not_in' => array($post->ID),
            'posts_per_page' => 5,
            'ignore_sticky_posts' => 1,
        );

        $related = new WP_Query($query_args);

        if ($related->have_posts()) {
            echo $args['before_widget'];
            echo $args['before_title'] . '📂 Articles from this Category' . $args['after_title'];
            echo '<ul class="same-category-posts-list">';

            while ($related->have_posts()) {
                $related->the_post();
                echo '<li>';
                echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                echo '<span class="post-date">' . get_the_date() . '</span>';
                echo '</li>';
            }

            echo '</ul>';
            echo $args['after_widget'];
            wp_reset_postdata();
        }
    }

    public function form($instance) {
        echo '<p>This widget shows posts from the same category. No settings needed.</p>';
    }

    public function update($new_instance, $old_instance) {
        return $old_instance;
    }
}

// Register the widget
function register_same_category_posts_widget() {
    register_widget('Same_Category_Posts_Widget');
}
add_action('widgets_init', 'register_same_category_posts_widget');

// Optional: Add basic CSS styling
function same_category_posts_widget_styles() {
    echo '<style>
        .same-category-posts-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .same-category-posts-list li {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .same-category-posts-list li a {
            font-weight: 600;
            color: #222;
            text-decoration: none;
            display: block;
        }
        .same-category-posts-list li .post-date {
            font-size: 12px;
            color: #888;
        }
    </style>';
}
add_action('wp_head', 'same_category_posts_widget_styles');
