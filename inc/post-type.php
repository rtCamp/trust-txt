<?php
/**
 * Post Type functionality for Trust.txt.
 *
 * @package Trust_Txt_Manager
 */

namespace Trusttxt;

/**
 * Register the `trusttxt` custom post type.
 *
 * @return void
 */
function register() {
	$args = array(
		'public'           => false,
		'hierarchical'     => false,
		'rewrite'          => false,
		'query_var'        => false,
		'delete_with_user' => false,
		'supports'         => array( 'revisions' ),
		'map_meta_cap'     => true,
		'capabilities'     => array(
			'create_posts'           => 'customize',
			'delete_others_posts'    => 'customize',
			'delete_post'            => 'customize',
			'delete_posts'           => 'customize',
			'delete_private_posts'   => 'customize',
			'delete_published_posts' => 'customize',
			'edit_others_posts'      => 'customize',
			'edit_post'              => 'customize',
			'edit_posts'             => 'customize',
			'edit_private_posts'     => 'customize',
			'edit_published_posts'   => 'edit_published_posts',
			'publish_posts'          => 'customize',
			'read'                   => 'read',
			'read_post'              => 'customize',
			'read_private_posts'     => 'customize',
		),
	);

	register_post_type(
		'trusttxt',
		array_merge(
			array(
				'labels' => array(
					'name'          => esc_html_x( 'Trust.txt', 'post type general name', 'trust-txt' ),
					'singular_name' => esc_html_x( 'Trust.txt', 'post type singular name', 'trust-txt' ),
				),
			),
			$args
		)
	);
}

add_action( 'init', __NAMESPACE__ . '\register' );
