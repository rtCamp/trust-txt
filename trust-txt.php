<?php
/**
 * Plugin Name: Trust.txt Manager
 * Description: Create, manage, and validate your Trust.txt from within WordPress, just like any other content asset. Requires PHP 5.3+ and WordPress 4.9+.
 * Version:     1.0
 * Author:      rtCamp
 * Author URI:  https://rtcamp.com
 * License:     GPLv2 or later
 * Text Domain: trust-txt
 *
 * @package Trust_Txt_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'TRUST_TXT_MANAGER_VERSION', '1.0' );
define( 'TRUST_TXT_MANAGE_CAPABILITY', 'edit_trust_txt' );
define( 'TRUST_TXT_MANAGER_POST_OPTION', 'trusttxt_post' );

require_once __DIR__ . '/inc/post-type.php';
require_once __DIR__ . '/inc/admin.php';
require_once __DIR__ . '/inc/save.php';

/**
 * Display the contents of /trust.txt when requested.
 *
 * @return void
 */
function rtcamp_display_trust_txt() {
	$request = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : false;
	if ( '/trust.txt' === $request ) {
		$post_id = get_option( TRUST_TXT_MANAGER_POST_OPTION );

		// Will fall through if no option found, likely to a 404.
		if ( ! empty( $post_id ) ) {
			$post = get_post( $post_id );

			if ( ! $post instanceof WP_Post ) {
				return;
			}

			header( 'Content-Type: text/plain' );
			$trusttxt = $post->post_content;

			/**
			 * Filter the trust.txt content.
			 *
			 * @since 1.0
			 *
			 * @param type  $trusttxt The existing trust.txt content.
			 */
			echo esc_html( apply_filters( 'trust_txt_content', $trusttxt ) );
			die();
		}
	}
}
add_action( 'init', 'rtcamp_display_trust_txt' );

/**
 * Add custom capabilities.
 *
 * @return void
 */
function add_trusttxt_capabilities() {
	$role = get_role( 'administrator' );
	if ( ! $role->has_cap( TRUST_TXT_MANAGE_CAPABILITY ) ) {
		$role->add_cap( TRUST_TXT_MANAGE_CAPABILITY );
	}
}
add_action( 'admin_init', 'add_trusttxt_capabilities' );
register_activation_hook( __FILE__, 'add_trusttxt_capabilities' );

/**
 * Remove custom capabilities when deactivating the plugin.
 *
 * @return void
 */
function remove_trusttxt_capabilities() {
	$role = get_role( 'administrator' );
	$role->remove_cap( TRUST_TXT_MANAGE_CAPABILITY );
}
register_deactivation_hook( __FILE__, 'remove_trusttxt_capabilities' );

/**
 * Add a query var to detect when trust.txt has been saved.
 *
 * @param array $qvars Array of query vars.
 *
 * @return array Array of query vars.
 */
function rtcamp_trust_txt_add_query_vars( $qvars ) {
	$qvars[] = 'trust_txt_saved';
	return $qvars;
}
add_filter( 'query_vars', 'rtcamp_trust_txt_add_query_vars' );
