<?php

/**
 * Register the Polylang Text Widget.
 */
class Polylang_Text_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'polylang_text_widget',
            __('Polylang Text Widget', 'polylang-text-widget'),
            ['description' => __('Displays text based on the selected language (English or Arabic).', 'polylang-text-widget')]
        );
    }

    public function widget($args, $instance) {
        $options = get_option('polylang_text_widget_settings', [
            'en' => '',
            'ar' => '',
        ]);

        // Get current language.
        $current_language = function_exists('pll_current_language') ? pll_current_language() : 'en';

        // Determine the text to display.
        $raw_text = $options[$current_language] ?? __('No content available for this language.', 'polylang-text-widget');

        // Parse and render Gutenberg blocks.
        $rendered_text = do_blocks($raw_text);

        echo $args['before_widget'];
        echo $args['before_title'] . __(' ', 'polylang-text-widget') . $args['after_title'];
        echo '<div>' . $rendered_text . '</div>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        echo '<p>' . __('Configure the text on the settings page.', 'polylang-text-widget') . '</p>';
    }
}

function register_polylang_text_widget() {
    register_widget('Polylang_Text_Widget');
}
add_action('widgets_init', 'register_polylang_text_widget');
