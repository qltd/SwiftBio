<?php
/**
 * License key fun.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_License {

	/**
	 * Holds any license error messages.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $errors = array();

	/**
	 * Holds any license success messages.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $success = array();

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Admin notices
		if ( !isset( $_GET['page'] ) || 'wpforms-settings' != $_GET['page'] ) {
			add_action( 'admin_notices', array( $this, 'notices' ) );
		}

		// Periodic background license check
		if ( $this->get() ) {
			$this->maybe_validate_key();
		}
	}

	/**
	 * Load the license key.
	 *
	 * @since 1.0.0
	 */
	public function get() {

		// Check for license key
		$key = wpforms_setting( 'key', false, 'wpforms_license' );

		// Allow wp-config constant to pass key
		if ( !$key && defined( 'WPFORMS_LICENSE_KEY' ) ) {
			$key = WPFORMS_LICENSE_KEY;
		}

		return $key;
	}

	/**
	 * Load the license key level.
	 *
	 * @since 1.0.0
	 */
	public function type() {

		$type = wpforms_setting( 'type', false, 'wpforms_license' );

		return $type;
	}

	/**
	 * Verifies a license key entered by the user.
	 *
	 * @since 1.0.0
	 */
	public function verify_key( $key = '' ) {

		if ( empty( $key ) ) {
			return false;
		}

		// Perform a request to verify the key.
		$verify = $this->perform_remote_request( 'verify-key', array( 'tgm-updater-key' => $key ) );

		// If it returns false, send back a generic error message and return.
		if ( ! $verify ) {
			$this->errors[] = __( 'There was an error connecting to the remote key API. Please try again later.', 'wpforms' );
			return;
		}

		// If an error is returned, set the error and return.
		if ( ! empty( $verify->error ) ) {
			$this->errors[] = $verify->error;
			return;
		}

		// Otherwise, our request has been done successfully. Update the option and set the success message.
		$option                = get_option( 'wpforms_license' );
		$option['key']         = $key;
		$option['type']        = isset( $verify->type ) ? $verify->type : $option['type'];
		$option['is_expired']  = false;
		$option['is_disabled'] = false;
		$option['is_invalid']  = false;
		$this->success[]       = isset( $verify->success ) ? $verify->success : __( 'Congratulations! This site is now receiving automatic updates.', 'wpforms' );
		update_option( 'wpforms_license', $option );
	}

	/**
	 * Maybe validates a license key entered by the user.
	 *
	 * @since 1.0.0
	 * @return null Return early if the transient has not expired yet.
	 */
	public function maybe_validate_key() {

		$key = $this->get();

		if ( !$key ) {
			return;
		}

		// Perform a request to validate the key.
		if ( false === ( $validate = get_transient( '_wpforms_validate_license' ) ) ) {
			// Only run every 12 hours.
			$timestamp = get_option( 'wpforms_license_updates' );
			if ( ! $timestamp ) {
				$timestamp = strtotime( '+12 hours' );
				update_option( 'wpforms_license_updates', $timestamp );
				$this->validate_key( $key );
			} else {
				$current_timestamp = time();
				if ( $current_timestamp < $timestamp ) {
					return;
				} else {
					update_option( 'wpforms_license_updates', strtotime( '+12 hours' ) );
					$this->validate_key( $key );
				}
			}
		}
	}

	/**
	 * Validates a license key entered by the user.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $forced Force to set contextual messages (false by default).
	 */
	public function validate_key( $key = '', $forced = false ) {

		$validate = $this->perform_remote_request( 'validate-key', array( 'tgm-updater-key' => $key ) );

		// If there was a basic API error in validation, only set the transient for 10 minutes before retrying.
		if ( ! $validate ) {
			// If forced, set contextual success message.
			if ( $forced ) {
				$this->errors[] = __( 'There was an error connecting to the remote key API. Please try again later.', 'wpforms' );
			}

			set_transient( '_wpforms_validate_license', false, 10 * MINUTE_IN_SECONDS );
			return;
		}

		// If a key or author error is returned, the license no longer exists or the user has been deleted, so reset license.
		if ( isset( $validate->key ) || isset( $validate->author ) ) {
			set_transient( '_wpforms_validate_license', false, DAY_IN_SECONDS );
			$option                = get_option( 'wpforms_license' );
			$option['is_expired']  = false;
			$option['is_disabled'] = false;
			$option['is_invalid']  = true;
			update_option( 'wpforms_license', $option );
			return;
		}

		// If the license has expired, set the transient and expired flag and return.
		if ( isset( $validate->expired ) ) {
			set_transient( '_wpforms_validate_license', false, DAY_IN_SECONDS );
			$option                = get_option( 'wpforms_license' );
			$option['is_expired']  = true;
			$option['is_disabled'] = false;
			$option['is_invalid']  = false;
			update_option( 'wpforms_license', $option );
			return;
		}

		// If the license is disabled, set the transient and disabled flag and return.
		if ( isset( $validate->disabled ) ) {
			set_transient( '_wpforms_validate_license', false, DAY_IN_SECONDS );
			$option                = get_option( 'wpforms_license' );
			$option['is_expired']  = false;
			$option['is_disabled'] = true;
			$option['is_invalid']  = false;
			update_option( 'wpforms_license', $option );
			return;
		}

		// If forced, set contextual success message.
		if ( $forced ) {
			$this->success[] = __( 'Your key has been refreshed successfully.', 'wpforms' );
		}

		// Otherwise, our check has returned successfully. Set the transient and update our license type and flags.
		set_transient( '_wpforms_validate_license', true, DAY_IN_SECONDS );
		$option                = get_option( 'wpforms_license' );
		$option['type']        = isset( $validate->type ) ? $validate->type : $option['type'];
		$option['is_expired']  = false;
		$option['is_disabled'] = false;
		$option['is_invalid']  = false;
		update_option( 'wpforms_license', $option );
	}

	/**
	 * Deactivates a license key entered by the user.
	 *
	 * @since 1.0.0
	 */
	public function deactivate_key() {

		$key = $this->get();

		if ( !$key ) {
			return;
		}

		// Perform a request to deactivate the key.
		$deactivate = $this->perform_remote_request( 'deactivate-key', array( 'tgm-updater-key' => $key ) );

		// If it returns false, send back a generic error message and return.
		if ( ! $deactivate ) {
			$this->errors[] = __( 'There was an error connecting to the remote key API. Please try again later.', 'wpforms' );
			return;
		}

		// If an error is returned, set the error and return.
		if ( ! empty( $deactivate->error ) ) {
			$this->errors[] = $deactivate->error;
			return;
		}

		// Otherwise, our request has been done successfully. Reset the option and set the success message.
		$this->success[] = isset( $deactivate->success ) ? $deactivate->success : __( 'You have deactivated the key from this site successfully.', 'wpforms' );
		update_option( 'wpforms_license', '' );
	}

	/**
	 * Returns possible license key error flag.
	 *
	 * @since 1.0.0
	 * @return bool True if there are license key errors, false otherwise.
	 */
	public function get_errors() {

		$option = get_option( 'wpforms_license' );
		return !empty( $option['is_expired'] ) || !empty( $option['is_disabled'] ) || !empty( $option['is_invalid'] );
	}

	/**
	 * Outputs any notices generated by the class.
	 *
	 * @since 1.0.0
	 */
	public function notices( $below_h2 = false ) {

		// Grab the option and output any nag dealing with license keys.
		$key      = $this->get();
		$option   = get_option( 'wpforms_license' );
		$below_h2 = $below_h2 ? 'below-h2' : '';

		// If there is no license key, output nag about ensuring key is set for automatic updates.
		if ( ! $key ) :
		?>
		<div class="notice notice-info <?php echo $below_h2; ?>">
			<p><?php printf( __( 'Please <a href="%s">enter and activate</a> your license key for WPForms to enable automatic updates.', 'wpforms' ), esc_url( add_query_arg( array( 'page' => 'wpforms-settings' ), admin_url( 'admin.php' ) ) ) ); ?></p>
		</div>
		<?php
		endif;

		// If a key has expired, output nag about renewing the key.
		if ( isset( $option['is_expired'] ) && $option['is_expired'] ) :
		?>
		<div class="error <?php echo $below_h2; ?>">
			<p><?php printf( __( 'Your license key for WPForms has expired. <a href="%s" target="_blank" rel="noopener">Please click here to renew your license key and continue receiving automatic updates.</a>', 'wpforms' ), 'https://wpforms.com/login/' ); ?></p>
		</div>
		<?php
		endif;

		// If a key has been disabled, output nag about using another key.
		if ( isset( $option['is_disabled'] ) && $option['is_disabled'] ) :
		?>
		<div class="error <?php echo $below_h2; ?>">
			<p><?php _e( 'Your license key for WPForms has been disabled. Please use a different key to continue receiving automatic updates.', 'wpforms' ); ?></p>
		</div>
		<?php
		endif;

		// If a key is invalid, output nag about using another key.
		if ( isset( $option['is_invalid'] ) && $option['is_invalid'] ) :
		?>
		<div class="error <?php echo $below_h2; ?>">
			<p><?php _e( 'Your license key for WPForms is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key to continue receiving automatic updates.', 'wpforms' ); ?></p>
		</div>
		<?php
		endif;

		// If there are any license errors, output them now.
		if ( ! empty( $this->errors ) ) :
		?>
		<div class="error <?php echo $below_h2; ?>">
			<p><?php echo implode( '<br>', $this->errors ); ?></p>
		</div>
		<?php
		endif;

		// If there are any success messages, output them now.
		if ( ! empty( $this->success ) ) :
		?>
		<div class="updated <?php echo $below_h2; ?>">
			<p><?php echo implode( '<br>', $this->success ); ?></p>
		</div>
		<?php
		endif;

	}

	/**
	 * Retrieves addons from the stored transient or remote server.
	 *
	 * @return 1.0.0
	 */
	public function addons( $force = false ) {

		$key = $this->get();

		if ( ! $key ) {
			return false;
		}

		if ( $force || false === ( $addons = get_transient( '_wpforms_addons' ) ) ) {
			$addons = $this->get_addons();
		}

		return $addons;
	}

	/**
	 * Pings the remote server for addons data.
	 *
	 * @since 1.0.0
	 * @param string $key The user license key.
	 * @return bool|array False if no key or failure, array of addon data otherwise.
	 */
	public function get_addons() {

		$key    = $this->get();
		$addons = $this->perform_remote_request( 'get-addons-data', array( 'tgm-updater-key' => $key ) );

		// If there was an API error, set transient for only 10 minutes.
		if ( ! $addons ) {
			set_transient( '_wpforms_addons', false, 10 * MINUTE_IN_SECONDS );
			return false;
		}
		// If there was an error retrieving the addons, set the error.
		if ( isset( $addons->error ) ) {
			set_transient( '_wpforms_addons', false, 10 * MINUTE_IN_SECONDS );
			return false;
		}
		// Otherwise, our request worked. Save the data and return it.
		set_transient( '_wpforms_addons', $addons, DAY_IN_SECONDS );
		return $addons;
	}

	/**
	 * Queries the remote URL via wp_remote_post and returns a json decoded response.
	 *
	 * @since 1.0.0
	 * @param string $action        The name of the $_POST action var.
	 * @param array $body           The content to retrieve from the remote URL.
	 * @param array $headers        The headers to send to the remote URL.
	 * @param string $return_format The format for returning content from the remote URL.
	 * @return string|bool          Json decoded response on success, false on failure.
	 */
	public function perform_remote_request( $action, $body = array(), $headers = array(), $return_format = 'json' ) {

		// Build the body of the request.
		$body = wp_parse_args(
			$body,
			array(
				'tgm-updater-action'     => $action,
				'tgm-updater-key'        => $body['tgm-updater-key'],
				'tgm-updater-wp-version' => get_bloginfo( 'version' ),
				'tgm-updater-referer'    => site_url(),
			)
		);
		$body = http_build_query( $body, '', '&' );

		// Build the headers of the request.
		$headers = wp_parse_args(
			$headers,
			array(
				'Content-Type'   => 'application/x-www-form-urlencoded',
				'Content-Length' => strlen( $body )
			)
		);

		// Setup variable for wp_remote_post.
		$post = array(
			'headers'   => $headers,
			'body'      => $body,
			'sslverify' => false,
		);

		// Perform the query and retrieve the response.
		$response      = wp_remote_post( WPFORMS_UPDATER_API, $post );
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		// Bail out early if there are any errors.
		if ( 200 != $response_code || is_wp_error( $response_body ) ) {
			return false;
		}

		// Return the json decoded content.
		return json_decode( $response_body );
	}
}