<?php
/*
Plugin Name: Dynamic Author Avatar and Posts Widget
Description: Dynamically displays the current post's author avatar and their latest or random posts.
Version: 1.1
Author: Dmitrii Chempalov
*/

class Dynamic_Author_Avatar_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'dynamic_author_avatar_posts_widget',
            __('Dynamic Author Avatar with Posts', 'text_domain'),
            array('description' => __('Displays the current post author\'s avatar and either random or latest posts.', 'text_domain'))
        );
    }

    // Widget form in admin
    public function form($instance) {
        $display_type = !empty($instance['display_type']) ? $instance['display_type'] : 'latest';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('display_type'); ?>"><?php _e('Display Posts Type:', 'text_domain'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('display_type'); ?>" name="<?php echo $this->get_field_name('display_type'); ?>">
                <option value="latest" <?php selected($display_type, 'latest'); ?>><?php _e('Latest Posts', 'text_domain'); ?></option>
                <option value="random" <?php selected($display_type, 'random'); ?>><?php _e('Random Posts', 'text_domain'); ?></option>
            </select>
        </p>
        <?php
    }

    // Save widget settings
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['display_type'] = (!empty($new_instance['display_type'])) ? strip_tags($new_instance['display_type']) : 'latest';
        return $instance;
    }

    // Display the widget in the frontend
    public function widget($args, $instance) {
        // Debug: Check if we're on a singular post/page
        if (!is_singular()) {
            echo '<p style="color: red;">Debug: Widget only works on singular posts or pages.</p>';
            return;
        }

        // Get the current post's author
        $author_id = get_the_author_meta('ID');
        if (!$author_id) {
            echo '<p style="color: red;">Debug: No author found for this post.</p>';
            return;
        }

        $author = get_user_by('ID', $author_id);
        if (!$author) {
            echo '<p style="color: red;">Debug: Author data could not be retrieved.</p>';
            return;
        }

        echo $args['before_widget'];

        $display_type = isset($instance['display_type']) ? $instance['display_type'] : 'latest';

        // Get the posts based on the display type
        $query_args = array(
            'author' => $author->ID,
            'post_status' => 'publish',
            'posts_per_page' => 5,
        );

        if ($display_type === 'random') {
            $query_args['orderby'] = 'rand';
        } else {
            $query_args['orderby'] = 'date';
            $query_args['order'] = 'DESC';
        }

        $author_posts = get_posts($query_args);

        $avatar = get_avatar($author->ID, 100);
        ?>
        <div class="author-widget">
            <div class="author-avatar" style="text-align: center;">
                <?php echo $avatar; ?>
            </div>
            <h4 style="text-align: center;"><?php echo esc_html($author->display_name); ?></h4>
            <ul class="author-posts">
                <?php if (!empty($author_posts)) : ?>
                    <?php foreach ($author_posts as $post): ?>
                        <li><a href="<?php echo get_permalink($post->ID); ?>"><?php echo esc_html($post->post_title); ?></a></li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li><?php _e('No posts available for this author.', 'text_domain'); ?></li>
                <?php endif; ?>
            </ul>
        </div>

        <style>
            .author-widget .author-avatar img {
                display: block;
                margin: 0 auto;
                border-radius: 50%;
            }
            .author-widget ul {
                margin-top: 10px;
                list-style: none;
                padding: 0;
            }
            .author-widget ul li {
                margin-bottom: 5px;
            }
        </style>
        <?php

        echo $args['after_widget'];
    }
}

// Register the widget
function register_dynamic_author_avatar_posts_widget() {
    register_widget('Dynamic_Author_Avatar_Posts_Widget');
}
add_action('widgets_init', 'register_dynamic_author_avatar_posts_widget');
