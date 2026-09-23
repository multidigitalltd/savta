<?php
/**
 * Leads are stored as a private custom post type so admins can see them in
 * wp-admin. No custom tables; personal data is limited to administrators.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

const SAVTA_LEAD_CPT       = 'savta_lead';
const SAVTA_LEAD_META_SLOT = '_savta_slot';

/**
 * Meta keys stored per lead (key => label).
 *
 * @return array<string, string>
 */
function savta_lead_fields(): array {
	return array(
		'_savta_phone'   => __( 'טלפון', 'savta' ),
		'_savta_city'    => __( 'עיר', 'savta' ),
		'_savta_age'     => __( 'גיל', 'savta' ),
		'_savta_contact' => __( 'דרך יצירת קשר', 'savta' ),
		'_savta_slot'    => __( 'מועד מבוקש', 'savta' ),
		'_savta_topic'   => __( 'נושא', 'savta' ),
	);
}

/**
 * Register the leads post type. Every capability maps to `manage_options`.
 *
 * @return void
 */
function savta_register_lead_cpt(): void {
	$caps = array(
		'edit_post'          => 'manage_options',
		'read_post'          => 'manage_options',
		'delete_post'        => 'manage_options',
		'edit_posts'         => 'manage_options',
		'edit_others_posts'  => 'manage_options',
		'publish_posts'      => 'do_not_allow',
		'read_private_posts' => 'manage_options',
		'delete_posts'       => 'manage_options',
		'create_posts'       => 'do_not_allow',
	);

	register_post_type(
		SAVTA_LEAD_CPT,
		array(
			'labels'              => array(
				'name'          => __( 'פניות', 'savta' ),
				'singular_name' => __( 'פנייה', 'savta' ),
				'menu_name'     => __( 'פניות', 'savta' ),
				'edit_item'     => __( 'פרטי הפנייה', 'savta' ),
				'search_items'  => __( 'חיפוש פניות', 'savta' ),
				'not_found'     => __( 'אין פניות עדיין.', 'savta' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'savta-overview',
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'query_var'           => false,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-heart',
			'menu_position'       => 25,
			'supports'            => array( 'title' ),
			'capabilities'        => $caps,
			'map_meta_cap'        => false,
		)
	);
}
add_action( 'init', 'savta_register_lead_cpt' );

/**
 * Admin list columns.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function savta_lead_columns( array $columns ): array {
	return array(
		'cb'             => $columns['cb'] ?? '',
		'title'          => __( 'שם', 'savta' ),
		'_savta_phone'   => __( 'טלפון', 'savta' ),
		'_savta_contact' => __( 'קשר', 'savta' ),
		'_savta_slot'    => __( 'מועד', 'savta' ),
		'_savta_city'    => __( 'עיר', 'savta' ),
		'date'           => __( 'התקבל', 'savta' ),
	);
}
add_filter( 'manage_' . SAVTA_LEAD_CPT . '_posts_columns', 'savta_lead_columns' );

/**
 * Admin list column content.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function savta_lead_column_content( string $column, int $post_id ): void {
	if ( ! str_starts_with( $column, '_savta_' ) ) {
		return;
	}
	$value = (string) get_post_meta( $post_id, $column, true );
	if ( '_savta_contact' === $column ) {
		$value = savta_contact_label( $value );
	}
	echo esc_html( $value );
}
add_action( 'manage_' . SAVTA_LEAD_CPT . '_posts_custom_column', 'savta_lead_column_content', 10, 2 );

/**
 * Human label for the preferred contact method.
 *
 * @param string $value 'phone' or 'whatsapp'.
 * @return string
 */
function savta_contact_label( string $value ): string {
	return 'whatsapp' === $value ? __( 'וואטסאפ', 'savta' ) : __( 'טלפון', 'savta' );
}

/**
 * Read-only details meta box on the lead screen.
 *
 * @return void
 */
function savta_lead_meta_box(): void {
	add_meta_box(
		'savta-lead-details',
		__( 'פרטי הפנייה', 'savta' ),
		'savta_render_lead_meta_box',
		SAVTA_LEAD_CPT,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_' . SAVTA_LEAD_CPT, 'savta_lead_meta_box' );

/**
 * Render the lead details table.
 *
 * @param WP_Post $post Lead post.
 * @return void
 */
function savta_render_lead_meta_box( WP_Post $post ): void {
	echo '<table class="widefat striped"><tbody>';
	foreach ( savta_lead_fields() as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		if ( '_savta_contact' === $key ) {
			$value = savta_contact_label( $value );
		}
		printf(
			'<tr><th scope="row" style="width:180px">%1$s</th><td>%2$s</td></tr>',
			esc_html( $label ),
			nl2br( esc_html( $value ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html applied before nl2br.
		);
	}
	echo '</tbody></table>';
}

/**
 * Invalidate the booked-slots cache whenever a lead is created, edited, trashed or deleted.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function savta_on_lead_change( int $post_id ): void {
	if ( get_post_type( $post_id ) === SAVTA_LEAD_CPT ) {
		savta_flush_slots_cache();
	}
}
add_action( 'save_post_' . SAVTA_LEAD_CPT, 'savta_on_lead_change' );
add_action( 'trashed_post', 'savta_on_lead_change' );
add_action( 'untrashed_post', 'savta_on_lead_change' );
add_action( 'deleted_post', 'savta_on_lead_change' );
