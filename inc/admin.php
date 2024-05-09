<?php
/**
 * Admin functionality for Trust.txt.
 *
 * @package Trust_Txt_Manager
 */

namespace TrustTxt;

/**
 * Enqueue any necessary scripts.
 *
 * @param  string $hook Hook name for the current screen.
 *
 * @return void
 */
function admin_enqueue_scripts( $hook ) {
	if ( ! preg_match( '/trusttxt-settings$/', $hook ) ) {
		return;
	}

	wp_enqueue_script(
		'trusttxt',
		esc_url( plugins_url( '/js/admin.js', dirname( __FILE__ ) ) ),
		array( 'jquery', 'wp-backbone', 'wp-codemirror' ),
		TRUST_TXT_MANAGER_VERSION,
		true
	);
	wp_enqueue_style( 'code-editor' );
	wp_enqueue_style(
		'trusttxt',
		esc_url( plugins_url( '/css/admin.css', dirname( __FILE__ ) ) ),
		array(),
		TRUST_TXT_MANAGER_VERSION
	);

	$strings = array(
		'error_message' => esc_html__( 'Your Trust.txt contains the following issues:', 'trust-txt' ),
		'unknown_error' => esc_html__( 'An unknown error occurred.', 'trust-txt' ),
	);

	wp_localize_script( 'trusttxt', 'trusttxt', $strings );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );

/**
 * Output some CSS directly in the head of the document.
 *
 * Should there ever be more than ~25 lines of CSS, this should become a separate file.
 *
 * @return void
 */
function admin_head_css() {
	?>
<style>
.CodeMirror {
	width: 100%;
	min-height: 60vh;
	height: calc( 100vh - 295px );
	border: 1px solid #ddd;
	box-sizing: border-box;
	}
</style>
	<?php
}
add_action( 'admin_head-settings_page_trusttxt-settings', __NAMESPACE__ . '\admin_head_css' );

/**
 * Appends a query argument to the edit url to make sure it is redirected to
 * the trust.txt screen.
 *
 * @since 1.0
 *
 * @param string $url Edit url.
 * @return string Edit url.
 */
function trust_txt_adjust_revisions_return_to_editor_link( $url ) {
	global $pagenow, $post;

	if ( 'revision.php' !== $pagenow || ! isset( $_REQUEST['trusttxt'] ) ) { // @codingStandardsIgnoreLine Nonce not required.
		return $url;
	}

	$type = 'trusttxt';

	return admin_url( 'options-general.php?page=' . $type . '-settings' );
}
add_filter( 'get_edit_post_link', __NAMESPACE__ . '\trust_txt_adjust_revisions_return_to_editor_link' );

/**
 * Modifies revisions data to preserve trusttxt argument used in determining
 * where to redirect user returning to editor.
 *
 * @since 1.0
 *
 * @param array $revisions_data The bootstrapped data for the revisions screen.
 * @return array Modified bootstrapped data for the revisions screen.
 */
function trusttxt_revisions_restore( $revisions_data ) {
	if ( isset( $_REQUEST['trusttxt'] ) ) { // @codingStandardsIgnoreLine Nonce not required.
		$revisions_data['restoreUrl'] = add_query_arg(
			'trusttxt',
			1,
			$revisions_data['restoreUrl']
		);
	}

	return $revisions_data;
}
add_filter( 'wp_prepare_revision_for_js', __NAMESPACE__ . '\trusttxt_revisions_restore' );

/**
 * Hide the revisions title with CSS, since WordPress always shows the title
 * field even if unchanged, and the title is not relevant for trust.txt.
 */
function admin_header_revisions_styles() {
	$current_screen = get_current_screen();

	if ( ! $current_screen || 'revision' !== $current_screen->id ) {
		return;
	}

	if ( ! isset( $_REQUEST['trusttxt'] ) ) { // @codingStandardsIgnoreLine Nonce not required.
		return;
	}

	?>
	<style>
		.revisions-diff .diff h3 {
			display: none;
		}
		.revisions-diff .diff table.diff:first-of-type {
			display: none;
		}
	</style>
	<?php

}
add_action( 'admin_head', __NAMESPACE__ . '\admin_header_revisions_styles' );

/**
 * Add admin menu page.
 *
 * @return void
 */
function admin_menu() {
	add_options_page(
		esc_html__( 'Trust.txt', 'trust-txt' ),
		esc_html__( 'Trust.txt', 'trust-txt' ),
		TRUST_TXT_MANAGE_CAPABILITY,
		'trusttxt-settings',
		__NAMESPACE__ . '\trusttxt_settings_screen'
	);

}
add_action( 'admin_menu', __NAMESPACE__ . '\admin_menu' );

/**
 * Set up settings screen for trust.txt.
 *
 * @return void
 */
function trusttxt_settings_screen() {
	$post_id = get_option( TRUST_TXT_MANAGER_POST_OPTION );

	$strings = array(
		'existing'      => __( 'Existing Trust.txt file found', 'trust-txt' ),
		'precedence'    => __( 'A trust.txt file on the server will take precedence over any content entered here. You will need to rename or remove the existing trust.txt file before you will be able to see any changes you make on this screen.', 'trust-txt' ),
		'errors'        => __( 'Your Trust.txt contains the following issues:', 'trust-txt' ),
		'screen_title'  => __( 'Manage Trust.txt', 'trust-txt' ),
		'content_label' => __( 'Trust.txt content', 'trust-txt' ),
	);

	$args = array(
		'post_type'  => 'trusttxt',
		'post_title' => 'Trust.txt',
		'option'     => TRUST_TXT_MANAGER_POST_OPTION,
		'action'     => 'trusttxt-save',
	);

	settings_screen( $post_id, $strings, $args );
}

/**
 * Output the settings screen for trust.txt file.
 *
 * @param int   $post_id Post ID associated with the file.
 * @param array $strings Translated strings that mention the specific file name.
 * @param array $args    Array of other necessary information to appropriately name items.
 *
 * @return void
 */
function settings_screen( $post_id, $strings, $args ) {
	$trust_path 	  = get_option( 'trust_custom_path' );
	$post             = false;
	$content          = false;
	$errors           = [];
	$revision_count   = 0;
	$last_revision_id = false;

	if ( $post_id ) {
		$post = get_post( $post_id );
	}

	if ( is_a( $post, 'WP_Post' ) ) {
		$content          = $post->post_content;
		$revisions        = wp_get_post_revisions( $post->ID );
		$revision_count   = count( $revisions );
		$last_revision    = array_shift( $revisions );
		$last_revision_id = $last_revision ? $last_revision->ID : false;
		$errors           = get_post_meta( $post->ID, 'trusttxt_errors', true );
		$revisions_link   = $last_revision_id ? admin_url( 'revision.php?trusttxt=1&revision=' . $last_revision_id ) : false;

	} else {

		// Create an initial post so the second save creates a comparable revision.
		$postarr = array(
			'post_title'   => $args['post_title'],
			'post_content' => '',
			'post_type'    => $args['post_type'],
			'post_status'  => 'publish',
		);

		$post_id = wp_insert_post( $postarr );
		if ( $post_id ) {
			update_option( $args['option'], $post_id );
		}
	}
	?>
<div class="wrap">
	<?php if ( ! empty( $errors ) ) : ?>
	<div class="notice notice-error trusttxt-notice">
		<p><strong><?php echo esc_html( $strings['errors'] ); ?></strong></p>
		<ul>
			<?php
			foreach ( $errors as $error ) {
				echo '<li>';

				// Errors were originally stored as an array.
				// This old style only needs to be accounted for here at runtime display.
				if ( isset( $error['message'] ) ) {
					$message = sprintf(
						/* translators: Error message output. 1: Line number, 2: Error message */
						__( 'Line %1$s: %2$s', 'trust-txt' ),
						$error['line'],
						$error['message']
					);

					echo esc_html( $message );
				} else {
					display_formatted_error( $error );
				}

				echo '</li>';
			}
			?>
		</ul>
	</div>
	<?php endif; ?>

	<h2><?php echo esc_html( $strings['screen_title'] ); ?></h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="trusttxt-settings-form">
		<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ) ? esc_attr( $post_id ) : ''; ?>" />
		<input type="hidden" name="trusttxt_type" value="<?php echo esc_attr( $args['post_type'] ); ?>" />
		<input type="hidden" name="action" value="<?php echo esc_attr( $args['action'] ); ?>" />
		<?php wp_nonce_field( 'trusttxt_save' ); ?>

		<label class="screen-reader-text" for="trusttxt_content"><?php echo esc_html( $strings['content_label'] ); ?></label>
		<textarea class="widefat code" rows="25" name="trusttxt" id="trusttxt_content"><?php echo esc_textarea( $content ); ?></textarea>
		<?php
		if ( $revision_count > 1 ) {
			?>
			<div class="misc-pub-section misc-pub-revisions">
			<?php
				echo wp_kses_post(
					sprintf(
						/* translators: Post revisions heading. 1: The number of available revisions */
						__( 'Revisions: <span class="trusttxt-revision-count">%s</span>', 'trust-txt' ),
						number_format_i18n( $revision_count )
					)
				);
			?>
				<a class="hide-if-no-js" href="<?php echo esc_url( $revisions_link ); ?>">
					<span aria-hidden="true">
						<?php echo esc_html( __( 'Browse', 'trust-txt' ) ); ?>
					</span> <span class="screen-reader-text">
						<?php echo esc_html( __( 'Browse revisions', 'trust-txt' ) ); ?>
					</span>
				</a>
		</div>
			<?php
		}
		?>
		<div id="trusttxt-notification-area"></div>

		<br />

		<label><input type="checkbox" name="trust_path" value="1" <?php checked( $trust_path, 1, true ); ?> /> Make trust.txt file accessible from .well-known directory.</label>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr( 'Save Changes' ); ?>">
			<span class="spinner" style="float:none;vertical-align:top"></span>
		</p>

	</form>

	<script type="text/template" id="tmpl-trusttext-notice">
		<# if ( ! _.isUndefined( data.errors ) ) { #>
		<div class="notice notice-error trusttxt-notice trusttxt-errors">
			<p><strong>{{ data.errors.error_message }}</strong></p>
			<# if ( ! _.isUndefined( data.errors.errors ) ) { #>
			<ul class="trusttxt-errors-items">
			<# _.each( data.errors.errors, function( error ) { #>
				<?php foreach ( array_keys( get_error_messages() ) as $error_type ) : ?>
				<# if ( "<?php echo esc_html( $error_type ); ?>" === error.type ) { #>
					<li>
						<?php
						display_formatted_error(
							array(
								'line'  => '{{error.line}}',
								'type'  => $error_type,
								'value' => '{{error.value}}',
							)
						);
						?>
					</li>
				<# } #>
				<?php endforeach; ?>
			<# } ); #>
			</ul>
			<# } #>
		</div>

		<# if ( _.isUndefined( data.saved ) && ! _.isUndefined( data.errors.errors ) ) { #>
		<p class="trusttxt-ays">
			<input id="trusttxt-ays-checkbox" name="trusttxt_ays" type="checkbox" value="y" />
			<label for="trusttxt-ays-checkbox">
				<?php esc_html_e( 'Update anyway?', 'trust-txt' ); ?>
			</label>
		</p>
		<# } #>

		<# } #>
	</script>



</div>

	<?php
}

/**
 * Take an error array and output it as a message.
 *
 * @param  array $error {
 *     Array of error message components.
 *
 *     @type int    $line    Line number of the error.
 *     @type string $type    Type of error.
 *     @type string $value   Optional. Value in question.
 * }
 *
 * @return string|void
 */
function display_formatted_error( $error ) {
	$messages = get_error_messages();

	if ( ! isset( $messages[ $error['type'] ] ) ) {
		return __( 'Unknown error', 'trusttxt' );
	}

	if ( ! isset( $error['value'] ) ) {
		$error['value'] = '';
	}

	$replacement_link = '<a href="https://journallist.net/reference-document-for-trust-txt-specifications" target="_blank">JournalList.net</a>';
	$message = sprintf( str_replace("JournalList.net", $replacement_link, esc_html( $messages[ $error['type'] ] ) ), '<code>' . esc_html( $error['value'] ) . '</code>' );

	printf(
		/* translators: Error message output. 1: Line number, 2: Error message */
		esc_html__( 'Line %1$s: %2$s', 'trust-txt' ),
		esc_html( $error['line'] ),
		wp_kses_post( $message )
	);
}

/**
 * Get all non-generic error messages, translated and with placeholders intact.
 *
 * @return array Associative array of error messages.
 */
function get_error_messages() {
	$messages = array(
		'invalid_variable'                  => __( 'The first word in this line is not one of the recognized variables. Please see JournalList.net for allowed variables.' ),
		'invalid_record'                    => __( 'This line does not conform with the trust.txt recommendations. Please read about what is recommended on JournalList.net.' ),
		'invalid_social'                    => __( '%s does not appear to be a valid social media domain' ),
		/* translators: %s: Domain */
		'invalid_domain'                    => __( '%s does not appear to be a valid domain' ),
		/* translators: %s: Domain */
		'invalid_disclosure'                => __( '%s does not appear to be a valid URL' ),
		'invalid_datatrainingallowed'       => __( 'Invalid input. Please enter "yes" or "no" (case-sensitive)' ),
		'invalid_datatrainingallowed_count' => __( 'Only one datatrainingallowed record is allowed' ),
		/* translators: %s: contact information */
		'invalid_contact'                   => __( '%s does not appear to be a valid contact information' ),
		'invalid_blank'                     => __( 'This field can not be blank. Either remove it or add valid value' ),
	);

	return $messages;
}

/**
 * Maybe display admin notices on the Trust.txt settings page.
 *
 * @return void
 */
function admin_notices() {
	if ( 'settings_page_trusttxt-settings' === get_current_screen()->base ) {
		$saved = __( 'Trust.txt saved', 'trust-txt' );
	} else {
		return;
	}

	if ( isset( $_GET['trust_txt_saved'] ) ) : // @codingStandardsIgnoreLine Nonce not required.
		?>
	<div class="notice notice-success trusttxt-notice trusttxt-saved">
		<p><?php echo esc_html( $saved ); ?></p>
	</div>
		<?php
	elseif ( isset( $_GET['revision'] ) ) : // @codingStandardsIgnoreLine Nonce not required.
		?>
	<div class="notice notice-success trusttxt-notice trusttxt-saved">
		<p><?php echo esc_html__( 'Revision restored', 'trust-txt' ); ?></p>
	</div>
		<?php
	endif;
}
add_action( 'admin_notices', __NAMESPACE__ . '\admin_notices' );
