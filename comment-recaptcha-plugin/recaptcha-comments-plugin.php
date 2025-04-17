<?php
/**
 * Plugin Name: Comment reCAPTCHA Integration
 * Description: Adds Google reCAPTCHA to WordPress comment form with admin settings.
 * Version: 1.0
 * Author: Dmitrii Chempalov
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load reCAPTCHA on single posts with comments
function cr_load_recaptcha_script() {
	if ( is_singular() && comments_open() ) {
		wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true );
	}
}
add_action( 'wp_enqueue_scripts', 'cr_load_recaptcha_script' );

// Add reCAPTCHA to comment form
function cr_add_recaptcha_to_comment_form( $fields ) {
	$site_key = get_option( 'cr_site_key' );
	if ( $site_key ) {
		echo '<div class="g-recaptcha" data-sitekey="' . esc_attr( $site_key ) . '"></div>';
	}
}
add_action( 'comment_form_after_fields', 'cr_add_recaptcha_to_comment_form' );
add_action( 'comment_form_logged_in_after', 'cr_add_recaptcha_to_comment_form' );

// Verify reCAPTCHA before processing comment
function cr_verify_recaptcha( $commentdata ) {
	if ( is_user_logged_in() ) return $commentdata;

	$secret_key = get_option( 'cr_secret_key' );

	if ( isset( $_POST['g-recaptcha-response'] ) && $secret_key ) {
		$response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
			'body' => array(
				'secret'   => $secret_key,
				'response' => sanitize_text_field( $_POST['g-recaptcha-response'] ),
				'remoteip' => $_SERVER['REMOTE_ADDR']
			)
		));

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! isset( $body['success'] ) || ! $body['success'] ) {
			wp_die( __( 'reCAPTCHA verification failed. Please go back and try again.', 'cr' ) );
		}
	} else {
		wp_die( __( 'Please complete the reCAPTCHA.', 'cr' ) );
	}

	return $commentdata;
}
add_filter( 'preprocess_comment', 'cr_verify_recaptcha' );

// Admin Settings Page
function cr_register_settings() {
	add_options_page(
		'Comment reCAPTCHA',
		'Comment reCAPTCHA',
		'manage_options',
		'cr-settings',
		'cr_settings_page'
	);

	register_setting( 'cr_settings_group', 'cr_site_key' );
	register_setting( 'cr_settings_group', 'cr_secret_key' );
}
add_action( 'admin_menu', 'cr_register_settings' );

// Settings Page HTML
function cr_settings_page() {
	?>
	<div class="wrap">
		<h1>Comment reCAPTCHA Settings</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'cr_settings_group' ); ?>
			<?php do_settings_sections( 'cr_settings_group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Site Key</th>
					<td><input type="text" name="cr_site_key" value="<?php echo esc_attr( get_option( 'cr_site_key' ) ); ?>" style="width: 400px;" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Secret Key</th>
					<td><input type="text" name="cr_secret_key" value="<?php echo esc_attr( get_option( 'cr_secret_key' ) ); ?>" style="width: 400px;" /></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}