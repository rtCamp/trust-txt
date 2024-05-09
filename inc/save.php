<?php
/**
 * Save functionality for Trust.txt.
 *
 * @package Trust_Txt_Manager
 */

namespace Trusttxt;

/**
 * Process and save the trust.txt data.
 *
 * Handles both AJAX and POST saves via `admin-ajax.php` and `admin-post.php` respectively.
 * AJAX calls output JSON; POST calls redirect back to the Trust.txt edit screen.
 *
 * @return void
 */
function save() {
	current_user_can( TRUST_TXT_MANAGE_CAPABILITY ) || die;
	check_admin_referer( 'trusttxt_save' );
	$_post      = stripslashes_deep( $_POST );
	$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

	$post_id = (int) $_post['post_id'];
	$ays     = isset( $_post['trusttxt_ays'] ) ? $_post['trusttxt_ays'] : null;

	// Different browsers use different line endings.
	$lines     = preg_split( '/\r\n|\r|\n/', $_post['trusttxt'] );
	$sanitized = array();
	$errors    = array();
	$response  = array();

	foreach ( $lines as $i => $line ) {
		$line_number = $i + 1;
		$result      = validate_line( $line, $line_number );

		$sanitized[] = $result['sanitized'];
		if ( ! empty( $result['errors'] ) ) {
			$errors = array_merge( $errors, $result['errors'] );
		}
	}

	$sanitized = implode( PHP_EOL, $sanitized );
	$postarr   = array(
		'ID'           => $post_id,
		'post_title'   => 'Trust.txt',
		'post_content' => $sanitized,
		'post_type'    => 'trusttxt',
		'post_status'  => 'publish',
		'meta_input'   => array(
			'trusttxt_errors' => $errors,
		),
	);

	update_option( 'trust_custom_path', sanitize_text_field( $_post['trust_path'] ) );

	if ( ! $doing_ajax || empty( $errors ) || 'y' === $ays ) {
		$post_id = wp_insert_post( $postarr );

		if ( $post_id ) {
			$response['saved'] = true;
		}
	}

	if ( $doing_ajax ) {
		$response['sanitized'] = $sanitized;

		if ( ! empty( $errors ) ) {
			$response['errors'] = $errors;
		}

		echo wp_json_encode( $response );
		die();
	}

	wp_safe_redirect( esc_url_raw( $_post['_wp_http_referer'] ) . '&updated=true' );
	exit;
}
add_action( 'admin_post_trusttxt-save', __NAMESPACE__ . '\save' );
add_action( 'wp_ajax_trusttxt-save', __NAMESPACE__ . '\save' );

/**
 * Validate a single line.
 *
 * @param string $line        The line to validate.
 * @param string $line_number The line number being evaluated.
 *
 * @return array {
 *     @type string $sanitized Sanitized version of the original line.
 *     @type array  $errors    Array of errors associated with the line.
 * }
 */
function validate_line( $line, $line_number ) {
	$domain_regex       = '/^(https?):\/\/((?=[a-z0-9-]{1,63}\.)(xn--)?[a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,63}(\/)?([a-z0-9-.\/_]*)$/i';
	$disclosure_regex   = '/^(https?):\/\/((?=[a-z0-9-]{1,63}\.)(xn--)?[a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,63}(\/)?([a-z0-9-.\/_]*.txt)$/i';
	$errors             = array();

	if ( empty( $line ) ) {
		$sanitized = '';
	} elseif ( 0 === strpos( $line, '#' ) ) { // This is a full-line comment.
		$sanitized = wp_strip_all_tags( $line );
	} elseif ( 1 < strpos( $line, '=' ) ) { // This is a variable declaration.
		// The spec currently supports member, belongto, control, controlledby, social, disclosure and contact
		if ( ! preg_match( '/^(member|belongto|control|controlledby|social|disclosure|contact|vendor|customer|datatrainingallowed)=/i', $line ) ) {
			$errors[] = array(
				'line' => $line_number,
				'type' => 'invalid_variable',
			);
		} elseif ( preg_match( '/^(member|belongto|control|controlledby|disclosure|social|contact|vendor|customer|datatrainingallowed)=/i', $line ) ) {
			// If we have a valid spec from the above list, check if the domain format is correct
			// This elseif condition is unnecessary but in future it will be needed

			// This is a hack to allow only one datatrainingallowed record.
			static $datatrainingallowed_count = 0;
	
			// Disregard any comments.
			$spec = explode( '#', $line );
			$spec = $spec[0];

			$spec = explode( '=', $spec );
			array_shift( $spec );

			$validation_regex = $domain_regex;
			$error_type       = 'invalid_domain';

			if ( 0 === stripos( $line, 'contact=' ) ) {
				// Use special contact regex for validation
				$validation_regex = '/^.+$/i';
				$error_type       = 'invalid_contact';
			} elseif ( 0 === stripos( $line, 'disclosure=' ) ) {
				// Use special contact regex for validation
				$validation_regex = $disclosure_regex;
				$error_type       = 'invalid_disclosure';
			} elseif ( 0 === stripos( $line, 'datatrainingallowed=' ) ) {
				// Use special contact regex for validation
				$validation_regex = '/^(yes|no)$/i';
				$error_type       = 'invalid_datatrainingallowed';

				// If we have more than one datatrainingallowed record, it's invalid.
				if ( 0 < $datatrainingallowed_count ) {
					$errors[] = array(
						'line' => $line_number,
						'type' => 'invalid_datatrainingallowed_count',
					);
				} else {
					$datatrainingallowed_count++;
				}
			}

			if ( '' === $spec[0] ) {
				$error_type = 'invalid_blank';
			}

			// If there's anything other than one piece left something's not right.
			if ( 1 !== count( $spec ) ||
				! preg_match( $validation_regex, $spec[0] )
			) {
				$spec = implode( '', $spec );
				$errors[]  = array(
					'line'  => $line_number,
					'type'  => $error_type,
					'value' => $spec,
				);
			}
		}

		$sanitized = wp_strip_all_tags( $line );

		unset( $spec );
	} else { // Data records: the most common.
		
		// Not a comment, variable declaration, or data record; therefore, invalid.
		// Early on we commented the line out for safety but it's kind of a weird thing to do with a JS AYS.
		$sanitized = wp_strip_all_tags( $line );

		$errors[] = array(
			'line' => $line_number,
			'type' => 'invalid_record',
		);
		unset( $record, $fields );
	}

	return array(
		'sanitized' => $sanitized,
		'errors'    => $errors,
	);
}

/**
 * Delete `trusttxt_errors` meta when restoring a revision.
 *
 * @param int $post_id Post ID, not revision ID.
 *
 * @return void
 */
function clear_error_meta( $post_id ) {
	delete_post_meta( $post_id, 'trusttxt_errors' );
}
add_action( 'wp_restore_post_revision', __NAMESPACE__ . '\clear_error_meta', 10, 1 );
