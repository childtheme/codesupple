<?php
/*
Plugin Name: Text Domain Manager
Plugin URI: https://github.com/childtheme/codesupple
Description: Automatically adds text domains for missing strings via a user-configurable settings page.
Version: 1.0
Author: Dmitrii Chempalov
Author URI: https://github.com/childtheme
License: GPL2
*/

// Hook to initialize the settings page and register settings
add_action('admin_menu', 'tdm_create_settings_page');
add_action('admin_init', 'tdm_register_settings');

// Hook to modify strings
add_filter('gettext', 'tdm_fix_missing_text_domain', 10, 3);

/**
 * Create settings page
 */
function tdm_create_settings_page() {
    add_options_page(
        'Text Domain Manager',
        'Text Domain Manager',
        'manage_options',
        'text-domain-manager',
        'tdm_settings_page_html'
    );
}

/**
 * Register settings
 */
function tdm_register_settings() {
    register_setting('tdm_settings_group', 'tdm_missing_strings');
}

/**
 * Settings page HTML
 */
/**
 * Settings page HTML
 */
function tdm_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>Text Domain Manager</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('tdm_settings_group');
            do_settings_sections('tdm_settings_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Missing Text</th>
                    <td>
                        <textarea name="tdm_missing_strings" rows="10" cols="50" class="large-text"><?php echo esc_textarea(get_option('tdm_missing_strings')); ?></textarea>
                        <p>Enter each missing text string on a new line, in the format:</p>
                        <p><code>Original Text | newsreader</code></p>
                        <p>Example:</p>
                        <p><code>add a comment | your-text-domain</code><br><code># of comments | newsreader</code></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


/**
 * Hook into gettext to replace missing text domain
 */
function tdm_fix_missing_text_domain($translated, $original, $domain) {
    // Get user-defined strings
    $missing_strings = get_option('tdm_missing_strings');
    
    if ($missing_strings) {
        // Split strings by new line
        $strings = explode("\n", $missing_strings);

        foreach ($strings as $string) {
            // Split by pipe to separate original text and text domain
            list($missing_text, $text_domain) = array_map('trim', explode('|', $string));

            // Check if the text matches and domain is empty
            if ($original == $missing_text && $domain == '') {
                return __($original, $text_domain);
            }
        }
    }

    return $translated;
}
function tdm_validate_settings($input) {
    $validated = [];
    $lines = explode("\n", $input);
    
    foreach ($lines as $line) {
        if (strpos($line, '|') === false) {
            add_settings_error('tdm_missing_strings', 'invalid_format', 'Each line must contain a text and a text domain separated by "|". Example: ');
            continue;
        }
        $validated[] = trim($line);
    }

    return implode("\n", $validated);
}

add_filter('pre_update_option_tdm_missing_strings', 'tdm_validate_settings');
