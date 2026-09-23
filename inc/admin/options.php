<?php
/**
 * Options layer for the dashboard: typed getters (with schema defaults),
 * schema-driven sanitisation, save/reset handlers and cache purging.
 *
 * Storage: three small autoloaded options (one read each per page load):
 * savta_content, savta_settings, savta_images.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

const SAVTA_OPT_CONTENT  = 'savta_content';
const SAVTA_OPT_SETTINGS = 'savta_settings';
const SAVTA_OPT_IMAGES   = 'savta_images';
const SAVTA_ADMIN_NONCE  = 'savta_admin';

/**
 * Flatten a grouped schema into key => field definition.
 *
 * @param array $schema Grouped schema.
 * @return array<string, array>
 */
function savta_schema_fields( array $schema ): array {
	$fields = array();
	foreach ( $schema as $group ) {
		foreach ( $group['fields'] as $key => $field ) {
			$fields[ $key ] = $field;
		}
	}
	return $fields;
}

/**
 * Defaults for a flattened schema.
 *
 * @param array $fields key => field.
 * @return array<string, mixed>
 */
function savta_schema_defaults( array $fields ): array {
	$out = array();
	foreach ( $fields as $key => $field ) {
		$out[ $key ] = $field['default'] ?? '';
	}
	return $out;
}

/**
 * Read an option merged over its defaults (cached per request).
 *
 * @param string $option Option name.
 * @return array<string, mixed>
 */
function savta_option_values( string $option ): array {
	static $cache = array();
	if ( isset( $cache[ $option ] ) ) {
		return $cache[ $option ];
	}
	switch ( $option ) {
		case SAVTA_OPT_CONTENT:
			$defaults = savta_schema_defaults( savta_schema_fields( savta_content_schema() ) );
			break;
		case SAVTA_OPT_SETTINGS:
			$defaults = savta_schema_defaults( savta_schema_fields( savta_settings_schema() ) );
			break;
		default:
			$defaults = array_fill_keys( array_keys( savta_image_schema() ), 0 );
			// Backwards compatibility with the first release's Customizer field.
			$legacy = (int) get_theme_mod( 'savta_first_savta_image', 0 );
			if ( $legacy ) {
				$defaults['img_first'] = $legacy;
			}
	}
	$saved            = get_option( $option, array() );
	$cache[ $option ] = array_merge( $defaults, is_array( $saved ) ? $saved : array() );
	return $cache[ $option ];
}

/**
 * Raw content value (string or array).
 *
 * @param string $key Field key.
 * @return mixed
 */
function savta_content( string $key ): mixed {
	$values = savta_option_values( SAVTA_OPT_CONTENT );
	return apply_filters( 'savta_content_' . $key, $values[ $key ] ?? '' );
}

/**
 * Content string.
 *
 * @param string $key Field key.
 * @return string
 */
function savta_text( string $key ): string {
	$value = savta_content( $key );
	return is_scalar( $value ) ? (string) $value : '';
}

/**
 * Echo an escaped content string.
 *
 * @param string $key Field key.
 * @return void
 */
function savta_e( string $key ): void {
	echo esc_html( savta_text( $key ) );
}

/**
 * Echo an escaped multi-line content string with <br> between lines.
 *
 * @param string $key Field key.
 * @return void
 */
function savta_e_br( string $key ): void {
	echo nl2br( esc_html( savta_text( $key ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html applied before nl2br.
}

/**
 * Content list (list / repeater field).
 *
 * @param string $key Field key.
 * @return array
 */
function savta_list( string $key ): array {
	$value = savta_content( $key );
	return is_array( $value ) ? array_values( $value ) : array();
}

/**
 * Setting value.
 *
 * @param string $key Setting key.
 * @return mixed
 */
function savta_setting( string $key ): mixed {
	$values = savta_option_values( SAVTA_OPT_SETTINGS );
	return apply_filters( 'savta_setting_' . $key, $values[ $key ] ?? null );
}

/**
 * Attachment ID for an image slot (0 = use the bundled image).
 *
 * @param string $key Image key.
 * @return int
 */
function savta_image_id( string $key ): int {
	$values = savta_option_values( SAVTA_OPT_IMAGES );
	$id     = (int) ( $values[ $key ] ?? 0 );
	return ( $id && wp_attachment_is_image( $id ) ) ? $id : 0;
}

/**
 * Whether a page section is switched on.
 *
 * @param string $slug Section slug (intro, about, how, who, efrat, first, notice, quotes, faq, signup).
 * @return bool
 */
function savta_section_on( string $slug ): bool {
	return (bool) savta_setting( 'show_' . $slug );
}

/**
 * Sanitise one value according to its field definition.
 *
 * @param array $field Field definition.
 * @param mixed $raw   Unslashed raw input.
 * @return mixed
 */
function savta_sanitize_field( array $field, mixed $raw ): mixed {
	switch ( $field['type'] ) {
		case 'text':
			return sanitize_text_field( (string) $raw );
		case 'lines':
		case 'textarea':
			return sanitize_textarea_field( (string) $raw );
		case 'list':
			$lines = (array) preg_split( '/\r\n|\r|\n/', (string) $raw );
			return array_values( array_filter( array_map( 'sanitize_text_field', $lines ), 'strlen' ) );
		case 'dates':
			$lines = (array) preg_split( '/\r\n|\r|\n/', (string) $raw );
			$out   = array();
			foreach ( $lines as $line ) {
				$line = trim( $line );
				if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $line ) && wp_checkdate( (int) substr( $line, 5, 2 ), (int) substr( $line, 8, 2 ), (int) substr( $line, 0, 4 ), $line ) ) {
					$out[] = $line;
				}
			}
			return array_values( array_unique( $out ) );
		case 'repeater':
			$rows = array();
			foreach ( (array) $raw as $row ) {
				if ( ! is_array( $row ) ) {
					continue;
				}
				$clean = array();
				$empty = true;
				foreach ( $field['fields'] as $sub_key => $sub ) {
					$clean[ $sub_key ] = savta_sanitize_field( array( 'type' => $sub[0] ), $row[ $sub_key ] ?? '' );
					if ( '' !== $clean[ $sub_key ] ) {
						$empty = false;
					}
				}
				if ( ! $empty ) {
					$rows[] = $clean;
				}
			}
			return $rows;
		case 'toggle':
			return ! empty( $raw );
		case 'number':
			$n = (int) $raw;
			return max( (int) ( $field['min'] ?? PHP_INT_MIN ), min( (int) ( $field['max'] ?? PHP_INT_MAX ), $n ) );
		case 'weekdays':
			$days = array_map( 'intval', (array) $raw );
			return array_values( array_filter( array_unique( $days ), fn( $d ) => $d >= 0 && $d <= 6 ) );
		case 'image':
			$id = absint( $raw );
			return ( $id && wp_attachment_is_image( $id ) ) ? $id : 0;
	}
	return '';
}

/**
 * Sanitise a whole submission against a flattened schema.
 * Every field in the schema is written (missing = empty / false), so toggles work.
 *
 * @param array $fields key => field definition.
 * @param array $input  Unslashed input.
 * @return array<string, mixed>
 */
function savta_sanitize_by_schema( array $fields, array $input ): array {
	$out = array();
	foreach ( $fields as $key => $field ) {
		$out[ $key ] = savta_sanitize_field( $field, $input[ $key ] ?? '' );
	}
	if ( isset( $fields['ga4_id'] ) && ! preg_match( '/^G-[A-Z0-9]{4,20}$/', (string) $out['ga4_id'] ) ) {
		$out['ga4_id'] = '';
	}
	if ( isset( $fields['lead_emails'] ) ) {
		$emails             = array_filter( array_map( 'sanitize_email', explode( ',', (string) $out['lead_emails'] ) ) );
		$out['lead_emails'] = implode( ', ', $emails );
	}
	if ( isset( $fields['slot_times'] ) ) {
		$out['slot_times'] = array_values( array_filter( (array) $out['slot_times'], fn( $t ) => (bool) preg_match( '/^\d{2}:\d{2}[–-]\d{2}:\d{2}$/u', $t ) ) );
		$out['slot_times'] = array_map( fn( $t ) => str_replace( '-', '–', $t ), $out['slot_times'] );
	}
	return $out;
}

/**
 * Handle "save" for the content / settings / images tabs.
 *
 * @return void
 */
function savta_handle_admin_save(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'אין לך הרשאה לפעולה זו.', 'savta' ), 403 );
	}
	check_admin_referer( SAVTA_ADMIN_NONCE );

	$tab   = isset( $_POST['savta_tab'] ) ? sanitize_key( wp_unslash( $_POST['savta_tab'] ) ) : '';
	$reset = ! empty( $_POST['savta_reset'] );
	$input = isset( $_POST['savta'] ) && is_array( $_POST['savta'] ) ? wp_unslash( $_POST['savta'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised per field below.

	switch ( $tab ) {
		case 'content':
			$option = SAVTA_OPT_CONTENT;
			$fields = savta_schema_fields( savta_content_schema() );
			break;
		case 'settings':
			$option = SAVTA_OPT_SETTINGS;
			$fields = savta_schema_fields( savta_settings_schema() );
			break;
		case 'images':
			$option = SAVTA_OPT_IMAGES;
			$fields = array_map(
				fn() => array(
					'type'    => 'image',
					'default' => 0,
				),
				savta_image_schema()
			);
			break;
		default:
			wp_die( esc_html__( 'בקשה לא תקינה.', 'savta' ), 400 );
	}

	if ( $reset ) {
		delete_option( $option );
	} else {
		update_option( $option, savta_sanitize_by_schema( $fields, $input ), true );
	}

	savta_purge_caches();

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'        => 'savta-' . $tab,
				'savta_saved' => $reset ? 'reset' : '1',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_savta_save', 'savta_handle_admin_save' );

/**
 * Purge everything that could serve stale content after a dashboard change.
 *
 * @return void
 */
function savta_purge_caches(): void {
	savta_flush_slots_cache();
	do_action( 'litespeed_purge_all' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- LiteSpeed Cache API.
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();
	}
	do_action( 'savta_purge_caches' );
}
