
<?php
/**
 * Plugin Name: Custom 410 and 404 Handler
 * Description: Handles 410 (Gone) and 404 (Not Found) errors with customizable templates, allowing users to edit text and design for both pages.
 * Version: 2.4
 * Author: Dmitrii Chempalov
 */

// Hook into template_include to load the custom 410 and 404 templates.
add_filter('template_include', 'custom_error_template');

function custom_error_template($template) {
    if (is_410()) {
        $custom_410_template = plugin_dir_path(__FILE__) . 'templates/410.php';
        if (file_exists($custom_410_template)) {
            return $custom_410_template;
        }
    } elseif (is_404()) {
        $custom_404_template = plugin_dir_path(__FILE__) . 'templates/404.php';
        if (file_exists($custom_404_template)) {
            return $custom_404_template;
        }
    }
    return $template;
}

// Define is_410 function to check if the current request is a 410.
function is_410() {
    global $wp_query;
    return isset($wp_query) && !empty($wp_query->is_410);
}

// Hook into template_redirect to set the appropriate status header.
add_action('template_redirect', 'set_error_status');

function set_error_status() {
    if (is_410()) {
        status_header(410);
    } elseif (is_404()) {
        status_header(404);
    }
}

// Register activation hook to create the error templates if they don't exist.
register_activation_hook(__FILE__, 'create_error_templates');

function create_error_templates() {
    $template_dir = plugin_dir_path(__FILE__) . 'templates';
    if (!file_exists($template_dir)) {
        mkdir($template_dir, 0755, true);
    }

    // Create default 410 template
    $template_410_content = '<?php
/**
 * Custom 410 Template
 */

get_header(); ?>

<div id="primary" class="content-area" style="color: <?php echo get_option("custom_410_text_color", "#000000"); ?>;">
    <main id="main" class="site-main" role="main">
        <section class="error-410">
            <header class="page-header">
                <h1 class="page-title"><?php echo esc_html(get_option("custom_410_title", "Page Gone")); ?></h1>
            </header>
            <div class="page-content">
                <p><?php echo esc_html(get_option("custom_410_message", "Sorry, the page you are looking for is no longer available.")); ?></p>
                <a href="<?php echo home_url(); ?>" class="button"><?php echo esc_html(get_option("custom_410_button_text", "Go to Home")); ?></a>
            </div>
        </section>
    </main>
</div>

<?php get_footer(); ?>';

    file_put_contents($template_dir . '/410.php', $template_410_content);

    // Create default 404 template
    $template_404_content = '<?php
/**
 * Custom 404 Template
 */

get_header(); ?>

<div id="primary" class="content-area" style="color: <?php echo get_option("custom_404_text_color", "#000000"); ?>;">
    <main id="main" class="site-main" role="main">
        <section class="error-404">
            <header class="page-header">
                <h1 class="page-title"><?php echo esc_html(get_option("custom_404_title", "Page Not Found")); ?></h1>
            </header>
            <div class="page-content">
                <p><?php echo esc_html(get_option("custom_404_message", "Sorry, the page you are looking for could not be found.")); ?></p>
                <a href="<?php echo home_url(); ?>" class="button"><?php echo esc_html(get_option("custom_404_button_text", "Go to Home")); ?></a>
            </div>
        </section>
    </main>
</div>

<?php get_footer(); ?>';

    file_put_contents($template_dir . '/404.php', $template_404_content);
}

// Add admin menu for plugin settings.
function custom_error_admin_menu() {
    add_options_page(
        'Custom Error Page Settings',
        'Error Page Settings',
        'manage_options',
        'custom-error-settings',
        'custom_error_settings_page'
    );
}
add_action('admin_menu', 'custom_error_admin_menu');

add_action('admin_enqueue_scripts', 'custom_error_admin_enqueue_scripts');
function custom_error_admin_enqueue_scripts($hook) {
    if ($hook !== 'settings_page_custom-error-settings') {
        return;
    }
    wp_enqueue_style('wp-color-picker'); // Enqueue WordPress color picker styles
    wp_enqueue_script('custom-error-color-picker', plugin_dir_url(__FILE__) . 'js/color-picker.js', ['wp-color-picker'], false, true);
}

// Render the settings page.
function custom_error_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['custom_error_settings_nonce']) && wp_verify_nonce($_POST['custom_error_settings_nonce'], 'custom_error_save_settings')) {
        update_option('custom_410_title', sanitize_text_field($_POST['custom_410_title']));
        update_option('custom_410_message', sanitize_text_field($_POST['custom_410_message']));
        update_option('custom_410_text_color', sanitize_hex_color($_POST['custom_410_text_color']));
        update_option('custom_410_button_text', sanitize_text_field($_POST['custom_410_button_text']));
        update_option('custom_410_button_color', sanitize_hex_color($_POST['custom_410_button_color']));
        update_option('custom_410_button_text_color', sanitize_hex_color($_POST['custom_410_button_text_color']));

        update_option('custom_404_title', sanitize_text_field($_POST['custom_404_title']));
        update_option('custom_404_message', sanitize_text_field($_POST['custom_404_message']));
        update_option('custom_404_text_color', sanitize_hex_color($_POST['custom_404_text_color']));
        update_option('custom_404_button_text', sanitize_text_field($_POST['custom_404_button_text']));
        update_option('custom_404_button_color', sanitize_hex_color($_POST['custom_404_button_color']));
        update_option('custom_404_button_text_color', sanitize_hex_color($_POST['custom_404_button_text_color']));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $settings = [
        '410_title' => get_option('custom_410_title', 'Page Gone'),
        '410_message' => get_option('custom_410_message', 'Sorry, the page you are looking for is no longer available.'),
        '410_text_color' => get_option('custom_410_text_color', '#000000'),
        '410_button_text' => get_option('custom_410_button_text', 'Go to Home'),
        '410_button_color' => get_option('custom_410_button_color', '#0073aa'),
        '410_button_text_color' => get_option('custom_410_button_text_color', '#ffffff'),
        '404_title' => get_option('custom_404_title', 'Page Not Found'),
        '404_message' => get_option('custom_404_message', 'Sorry, the page you are looking for could not be found.'),
        '404_text_color' => get_option('custom_404_text_color', '#000000'),
        '404_button_text' => get_option('custom_404_button_text', 'Go to Home'),
        '404_button_color' => get_option('custom_404_button_color', '#0073aa'),
        '404_button_text_color' => get_option('custom_404_button_text_color', '#ffffff'),
    ];

    ?>
    <div class="wrap">
        <h1>Custom Error Page Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('custom_error_save_settings', 'custom_error_settings_nonce'); ?>

            <h2>410 Page Settings</h2>
            <table class="form-table">
                <tr>
                    <th>Page Title</th>
                    <td><input type="text" name="custom_410_title" value="<?php echo esc_attr($settings['410_title']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td><textarea name="custom_410_message" rows="3" class="large-text"><?php echo esc_textarea($settings['410_message']); ?></textarea></td>
                </tr>
                <tr>
                    <th>Text Color</th>
                    <td><input type="text" name="custom_410_text_color" value="<?php echo esc_attr($settings['410_text_color']); ?>" class="color-field"></td>
                </tr>
                <tr>
                    <th>Button Text</th>
                    <td><input type="text" name="custom_410_button_text" value="<?php echo esc_attr($settings['410_button_text']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Button Color</th>
                    <td><input type="text" name="custom_410_button_color" value="<?php echo esc_attr($settings['410_button_color']); ?>" class="color-field"></td>
                </tr>
                <tr>
                    <th>Button Text Color</th>
                    <td><input type="text" name="custom_410_button_text_color" value="<?php echo esc_attr($settings['410_button_text_color']); ?>" class="color-field"></td>
                </tr>
            </table>

            <h2>404 Page Settings</h2>
            <table class="form-table">
                <tr>
                    <th>Page Title</th>
                    <td><input type="text" name="custom_404_title" value="<?php echo esc_attr($settings['404_title']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td><textarea name="custom_404_message" rows="3" class="large-text"><?php echo esc_textarea($settings['404_message']); ?></textarea></td>
                </tr>
                <tr>
                    <th>Text Color</th>
                    <td><input type="text" name="custom_404_text_color" value="<?php echo esc_attr($settings['404_text_color']); ?>" class="color-field"></td>
                </tr>
                <tr>
                    <th>Button Text</th>
                    <td><input type="text" name="custom_404_button_text" value="<?php echo esc_attr($settings['404_button_text']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Button Color</th>
                    <td><input type="text" name="custom_404_button_color" value="<?php echo esc_attr($settings['404_button_color']); ?>" class="color-field"></td>
                </tr>
                <tr>
                    <th>Button Text Color</th>
                    <td><input type="text" name="custom_404_button_text_color" value="<?php echo esc_attr($settings['404_button_text_color']); ?>" class="color-field"></td>
                </tr>
            </table>

            <?php submit_button('Save Changes'); ?>
        </form>
    </div>
    <?php
}
?>
