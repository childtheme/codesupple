<?php
/*
Plugin Name: Enhanced Dynamic Author Widget
Description: Dynamically displays the current post's author avatar, biography, configurable post settings.
Version: 1.0.1
Author: Dmitrii Chempalov
*/

class Enhanced_Dynamic_Author_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'enhanced_dynamic_author_widget',
            __('Enhanced Dynamic Author Widget', 'text_domain'),
            array('description' => __('Displays the author avatar, biography, and configurable post settings, with options to show post dates and categories.', 'text_domain'))
        );

        // Add settings page
        add_action('admin_menu', [$this, 'register_settings_page']);
        add_action('admin_init', [$this, 'register_plugin_settings']);
    }

    public function register_settings_page() {
        add_options_page(
            __('Enhanced Dynamic Author Widget Settings', 'text_domain'),
            __('Dynamic Author Widget', 'text_domain'),
            'manage_options',
            'enhanced_dynamic_author_widget',
            [$this, 'settings_page']
        );
    }

    public function register_plugin_settings() {
        register_setting('enhanced_dynamic_author_widget_settings', 'author_widget_settings');

        add_settings_section('general_settings', __('General Settings', 'text_domain'), null, 'enhanced_dynamic_author_widget');

        add_settings_field('bio_max_length', __('Max Bio Length', 'text_domain'), [$this, 'bio_max_length_field'], 'enhanced_dynamic_author_widget', 'general_settings');
        add_settings_field('avatar_size', __('Avatar Size (px)', 'text_domain'), [$this, 'avatar_size_field'], 'enhanced_dynamic_author_widget', 'general_settings');
        add_settings_field('posts_per_page', __('Number of Posts', 'text_domain'), [$this, 'posts_per_page_field'], 'enhanced_dynamic_author_widget', 'general_settings');
        add_settings_field('post_display_type', __('Post Display Type', 'text_domain'), [$this, 'post_display_type_field'], 'enhanced_dynamic_author_widget', 'general_settings');
        add_settings_field('show_post_date', __('Show Post Date', 'text_domain'), [$this, 'show_post_date_field'], 'enhanced_dynamic_author_widget', 'general_settings');
        add_settings_field('show_post_category', __('Show Post Category', 'text_domain'), [$this, 'show_post_category_field'], 'enhanced_dynamic_author_widget', 'general_settings');
    }

    public function bio_max_length_field() {
        $options = get_option('author_widget_settings');
        $bio_max_length = isset($options['bio_max_length']) ? $options['bio_max_length'] : 100;
        echo '<input type="number" name="author_widget_settings[bio_max_length]" value="' . esc_attr($bio_max_length) . '" />';
    }

    public function avatar_size_field() {
        $options = get_option('author_widget_settings');
        $avatar_size = isset($options['avatar_size']) ? $options['avatar_size'] : 100;
        echo '<input type="number" name="author_widget_settings[avatar_size]" value="' . esc_attr($avatar_size) . '" />';
    }

    public function posts_per_page_field() {
        $options = get_option('author_widget_settings');
        $posts_per_page = isset($options['posts_per_page']) ? $options['posts_per_page'] : 5;
        echo '<input type="number" name="author_widget_settings[posts_per_page]" value="' . esc_attr($posts_per_page) . '" />';
    }

    public function post_display_type_field() {
        $options = get_option('author_widget_settings');
        $display_type = isset($options['post_display_type']) ? $options['post_display_type'] : 'latest';
        ?>
        <select name="author_widget_settings[post_display_type]">
            <option value="latest" <?php selected($display_type, 'latest'); ?>><?php _e('Latest Posts', 'text_domain'); ?></option>
            <option value="random" <?php selected($display_type, 'random'); ?>><?php _e('Random Posts', 'text_domain'); ?></option>
        </select>
        <?php
    }

    public function show_post_date_field() {
        $options = get_option('author_widget_settings');
        $show_post_date = isset($options['show_post_date']) ? $options['show_post_date'] : 0;
        echo '<input type="checkbox" name="author_widget_settings[show_post_date]" value="1" ' . checked(1, $show_post_date, false) . ' />';
    }

    public function show_post_category_field() {
        $options = get_option('author_widget_settings');
        $show_post_category = isset($options['show_post_category']) ? $options['show_post_category'] : 0;
        echo '<input type="checkbox" name="author_widget_settings[show_post_category]" value="1" ' . checked(1, $show_post_category, false) . ' />';
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Enhanced Dynamic Author Widget Settings', 'text_domain'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('enhanced_dynamic_author_widget_settings');
                do_settings_sections('enhanced_dynamic_author_widget');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function widget($args, $instance) {
        $options = get_option('author_widget_settings');

        $bio_max_length = isset($options['bio_max_length']) ? intval($options['bio_max_length']) : 100;
        $avatar_size = isset($options['avatar_size']) ? intval($options['avatar_size']) : 100;
        $posts_per_page = isset($options['posts_per_page']) ? intval($options['posts_per_page']) : 5;
        $display_type = isset($options['post_display_type']) ? $options['post_display_type'] : 'latest';
        $show_post_date = isset($options['show_post_date']) ? $options['show_post_date'] : 0;
        $show_post_category = isset($options['show_post_category']) ? $options['show_post_category'] : 0;

        if (!is_singular()) {
            echo '<p style="color:red;">' . __('Widget only works on singular posts or pages.', 'text_domain') . '</p>';
            return;
        }

        $author_id = get_the_author_meta('ID');
        if (!$author_id) {
            echo '<p style="color:red;">' . __('No author found for this post.', 'text_domain') . '</p>';
            return;
        }

        $author = get_user_by('ID', $author_id);
        if (!$author) {
            echo '<p style="color:red;">' . __('Author data could not be retrieved.', 'text_domain') . '</p>';
            return;
        }

        $bio = get_the_author_meta('description', $author_id);
        $short_bio = $bio;

        if (strlen($bio) > $bio_max_length) {
            $short_bio = substr($bio, 0, $bio_max_length) . '... <a href="' . get_author_posts_url($author_id) . '">' . __('Read More', 'text_domain') . '</a>';
        }

        $query_args = [
            'author' => $author_id,
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
        ];

        if ($display_type === 'random') {
            $query_args['orderby'] = 'rand';
        } else {
            $query_args['orderby'] = 'date';
            $query_args['order'] = 'DESC';
        }

        $author_posts = get_posts($query_args);

        echo $args['before_widget'];
        echo '<div class="author-widget" style="text-align: center;">';
        echo '<div class="author-avatar">' . get_avatar($author_id, $avatar_size) . '</div>';
        echo '<h4>' . esc_html($author->display_name) . '</h4>';
        if (!empty($short_bio)) {
            echo '<p>' . $short_bio . '</p>';
        }
        echo '<ul>';
        foreach ($author_posts as $post) {
            echo '<li>';
            echo '<a href="' . get_permalink($post->ID) . '">' . esc_html($post->post_title) . '</a>';
            if ($show_post_date) {
                echo ' <span style="font-size: 12px; color: gray;">(' . get_the_date('', $post->ID) . ')</span>';
            }
            if ($show_post_category) {
                $categories = get_the_category($post->ID);
                if (!empty($categories)) {
                    echo ' <span style="font-size: 12px; color: gray;">- ' . esc_html($categories[0]->name) . '</span>';
                }
            }
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
        echo $args['after_widget'];
    }
}

function register_enhanced_dynamic_author_widget() {
    register_widget('Enhanced_Dynamic_Author_Widget');
}
add_action('widgets_init', 'register_enhanced_dynamic_author_widget');
?>
