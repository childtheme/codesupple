<?php

/**
 * Register the settings page under the "Settings" menu.
 */
function polylang_text_widget_settings_page() {
    add_options_page(
        __('Polylang Text Widget Settings', 'polylang-text-widget'),
        __('Polylang Text', 'polylang-text-widget'),
        'manage_options',
        'polylang-text-widget',
        'polylang_text_widget_render_settings_page'
    );
}
add_action('admin_menu', 'polylang_text_widget_settings_page');

/**
 * Enqueue the necessary scripts and styles for the Gutenberg editor in the admin.
 */
function polylang_text_widget_enqueue_editor_assets() {
    wp_enqueue_editor();
    wp_enqueue_script('wp-editor');
    wp_enqueue_script('wp-blocks');
    wp_enqueue_script('wp-components');
    wp_enqueue_script('wp-i18n');
    wp_enqueue_style('wp-edit-blocks');
}
add_action('admin_enqueue_scripts', 'polylang_text_widget_enqueue_editor_assets');

/**
 * Render the settings page.
 */
function polylang_text_widget_render_settings_page() {
    $options = get_option('polylang_text_widget_settings', [
        'en' => '',
        'ar' => '',
    ]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        check_admin_referer('polylang_text_widget_save_settings');

        $options['en'] = wp_kses_post($_POST['text_en']);
        $options['ar'] = wp_kses_post($_POST['text_ar']);

        update_option('polylang_text_widget_settings', $options);

        echo '<div class="updated"><p>' . __('Settings saved.', 'polylang-text-widget') . '</p></div>';
    }

    ?>
    <div class="wrap">
        <h1><?php _e('Polylang Text Widget Settings', 'polylang-text-widget'); ?></h1>
        <form method="POST">
            <?php wp_nonce_field('polylang_text_widget_save_settings'); ?>
            <h2><?php _e('English Content', 'polylang-text-widget'); ?></h2>
            <?php
            wp_editor(
                $options['en'],
                'text_en', // ID for the editor.
                [
                    'textarea_name' => 'text_en',
                    'media_buttons' => true, // Show Add Media button.
                    'tinymce' => true, // Enable TinyMCE editor.
                    'quicktags' => true, // Show quicktags (HTML editor toolbar).
                ]
            );
            ?>
            <h2><?php _e('Arabic Content', 'polylang-text-widget'); ?></h2>
            <?php
            wp_editor(
                $options['ar'],
                'text_ar', // ID for the editor.
                [
                    'textarea_name' => 'text_ar',
                    'media_buttons' => true,
                    'tinymce' => true,
                    'quicktags' => true,
                ]
            );
            ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
