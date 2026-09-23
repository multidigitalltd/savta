<?php
/**
 * Admin menu + schema-driven tab pages (content, images, settings).
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the top-level menu and its tabs.
 *
 * @return void
 */
function savta_admin_menu(): void {
	add_menu_page(
		__( 'סבתא על הספסל', 'savta' ),
		__( 'סבתא על הספסל', 'savta' ),
		'manage_options',
		'savta-overview',
		'savta_render_overview_page',
		'dashicons-heart',
		3
	);
	add_submenu_page( 'savta-overview', __( 'סקירה', 'savta' ), __( 'סקירה', 'savta' ), 'manage_options', 'savta-overview', 'savta_render_overview_page' );
	add_submenu_page( 'savta-overview', __( 'תוכן העמוד', 'savta' ), __( 'תוכן העמוד', 'savta' ), 'manage_options', 'savta-content', 'savta_render_content_page' );
	add_submenu_page( 'savta-overview', __( 'תמונות', 'savta' ), __( 'תמונות', 'savta' ), 'manage_options', 'savta-images', 'savta_render_images_page' );
	add_submenu_page( 'savta-overview', __( 'הגדרות', 'savta' ), __( 'הגדרות', 'savta' ), 'manage_options', 'savta-settings', 'savta_render_settings_page' );
}
add_action( 'admin_menu', 'savta_admin_menu' );

/**
 * Admin assets, only on the theme's own screens.
 *
 * @param string $hook Current admin page hook.
 * @return void
 */
function savta_admin_assets( string $hook ): void {
	if ( ! str_contains( $hook, 'savta-' ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'savta-admin', SAVTA_URI . '/assets/css/admin.css', array(), SAVTA_VERSION );
	wp_enqueue_script( 'savta-admin', SAVTA_URI . '/assets/js/admin.js', array(), SAVTA_VERSION, array( 'in_footer' => true ) );
	wp_localize_script(
		'savta-admin',
		'savtaAdmin',
		array(
			'chooseImage' => __( 'בחירת תמונה', 'savta' ),
			'useImage'    => __( 'שימוש בתמונה', 'savta' ),
			'confirmRm'   => __( 'להסיר את השורה?', 'savta' ),
			'unsaved'     => __( 'יש שינויים שלא נשמרו.', 'savta' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'savta_admin_assets' );

/**
 * Page header with the tab bar and the "saved" notice.
 *
 * @param string $current Current tab slug.
 * @return void
 */
function savta_admin_header( string $current ): void {
	$tabs = array(
		'overview' => __( 'סקירה', 'savta' ),
		'content'  => __( 'תוכן העמוד', 'savta' ),
		'images'   => __( 'תמונות', 'savta' ),
		'settings' => __( 'הגדרות', 'savta' ),
	);
	echo '<div class="wrap savta-admin"><h1>' . esc_html__( 'סבתא על הספסל', 'savta' ) . ' <span class="savta-admin__version">v' . esc_html( SAVTA_VERSION ) . '</span></h1>';
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display-only flag set by our own redirect.
	$saved = isset( $_GET['savta_saved'] ) ? sanitize_key( wp_unslash( $_GET['savta_saved'] ) ) : '';
	if ( '1' === $saved ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'נשמר. המטמון נוקה.', 'savta' ) . '</p></div>';
	} elseif ( 'reset' === $saved ) {
		echo '<div class="notice notice-info is-dismissible"><p>' . esc_html__( 'שוחזרו ערכי ברירת המחדל.', 'savta' ) . '</p></div>';
	} elseif ( 'purged' === $saved ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'המטמון נוקה.', 'savta' ) . '</p></div>';
	}
	echo '<nav class="nav-tab-wrapper" aria-label="' . esc_attr__( 'לשוניות', 'savta' ) . '">';
	foreach ( $tabs as $slug => $label ) {
		printf(
			'<a href="%1$s" class="nav-tab %2$s"%3$s>%4$s</a>',
			esc_url( admin_url( 'admin.php?page=savta-' . $slug ) ),
			esc_attr( $slug === $current ? 'nav-tab-active' : '' ),
			$slug === $current ? ' aria-current="page"' : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute.
			esc_html( $label )
		);
	}
	printf( '<a href="%1$s" class="nav-tab">%2$s</a>', esc_url( admin_url( 'edit.php?post_type=' . SAVTA_LEAD_CPT ) ), esc_html__( 'פניות', 'savta' ) );
	echo '</nav>';
}

/**
 * Open the save form for a tab.
 *
 * @param string $tab Tab slug.
 * @return void
 */
function savta_admin_form_open( string $tab ): void {
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" class="savta-form" data-savta-form>';
	echo '<input type="hidden" name="action" value="savta_save"><input type="hidden" name="savta_tab" value="' . esc_attr( $tab ) . '">';
	wp_nonce_field( SAVTA_ADMIN_NONCE );
}

/**
 * Close the save form with the action bar.
 *
 * @return void
 */
function savta_admin_form_close(): void {
	echo '<div class="savta-actions">';
	submit_button( __( 'שמירת שינויים', 'savta' ), 'primary large', 'submit', false );
	echo ' <button type="submit" name="savta_reset" value="1" class="button button-secondary" data-confirm="' . esc_attr__( 'לשחזר את כל השדות בלשונית זו לברירת המחדל?', 'savta' ) . '">' . esc_html__( 'שחזור ברירת מחדל', 'savta' ) . '</button>';
	echo '</div></form></div>';
}

/**
 * Render one control.
 *
 * @param string $key   Field key.
 * @param array  $field Field definition.
 * @param mixed  $value Current value.
 * @return void
 */
function savta_render_field( string $key, array $field, mixed $value ): void {
	$name = 'savta[' . $key . ']';
	$id   = 'savta-' . $key;
	switch ( $field['type'] ) {
		case 'text':
			printf( '<input type="text" class="regular-text" id="%1$s" name="%2$s" value="%3$s">', esc_attr( $id ), esc_attr( $name ), esc_attr( (string) $value ) );
			break;
		case 'lines':
			printf( '<textarea id="%1$s" name="%2$s" rows="2" class="large-text">%3$s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( (string) $value ) );
			break;
		case 'textarea':
			printf( '<textarea id="%1$s" name="%2$s" rows="4" class="large-text">%3$s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( (string) $value ) );
			break;
		case 'list':
		case 'dates':
			printf( '<textarea id="%1$s" name="%2$s" rows="%4$d" class="large-text code">%3$s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( implode( "\n", (array) $value ) ), absint( max( 3, min( 12, count( (array) $value ) + 1 ) ) ) );
			break;
		case 'toggle':
			printf( '<label class="savta-toggle"><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s> <span>%4$s</span></label>', esc_attr( $id ), esc_attr( $name ), checked( (bool) $value, true, false ), esc_html__( 'פעיל', 'savta' ) );
			break;
		case 'number':
			printf( '<input type="number" class="small-text" id="%1$s" name="%2$s" value="%3$d" min="%4$d" max="%5$d">', esc_attr( $id ), esc_attr( $name ), (int) $value, (int) ( $field['min'] ?? 0 ), (int) ( $field['max'] ?? 9999 ) );
			break;
		case 'weekdays':
			$names = savta_weekday_names();
			echo '<fieldset class="savta-weekdays"><legend class="screen-reader-text">' . esc_html( $field['label'] ) . '</legend>';
			foreach ( $names as $n => $label ) {
				printf( '<label><input type="checkbox" name="%1$s[]" value="%2$d" %3$s> %4$s</label>', esc_attr( $name ), (int) $n, checked( in_array( $n, (array) $value, true ), true, false ), esc_html( $label ) );
			}
			echo '</fieldset>';
			break;
		case 'repeater':
			savta_render_repeater( $key, $field, (array) $value );
			break;
		case 'image':
			savta_render_image_field( $key, (int) $value );
			break;
	}
	if ( ! empty( $field['help'] ) ) {
		echo '<p class="description">' . esc_html( $field['help'] ) . '</p>';
	}
}

/**
 * Repeater control (rows with sub-fields, add/remove/reorder via admin.js).
 *
 * @param string $key   Field key.
 * @param array  $field Field definition.
 * @param array  $rows  Current rows.
 * @return void
 */
function savta_render_repeater( string $key, array $field, array $rows ): void {
	$render_row = function ( $index, array $row ) use ( $key, $field ): void {
		echo '<div class="savta-row" data-row>';
		foreach ( $field['fields'] as $sub_key => $sub ) {
			$name = 'savta[' . $key . '][' . $index . '][' . $sub_key . ']';
			echo '<label class="savta-row__field"><span>' . esc_html( $sub[1] ) . '</span>';
			if ( 'text' === $sub[0] ) {
				printf( '<input type="text" name="%1$s" value="%2$s">', esc_attr( $name ), esc_attr( (string) ( $row[ $sub_key ] ?? '' ) ) );
			} else {
				printf( '<textarea name="%1$s" rows="%3$d">%2$s</textarea>', esc_attr( $name ), esc_textarea( (string) ( $row[ $sub_key ] ?? '' ) ), 'lines' === $sub[0] ? 2 : 3 );
			}
			echo '</label>';
		}
		echo '<span class="savta-row__tools"><button type="button" class="button" data-up aria-label="' . esc_attr__( 'למעלה', 'savta' ) . '">&#8593;</button><button type="button" class="button" data-down aria-label="' . esc_attr__( 'למטה', 'savta' ) . '">&#8595;</button><button type="button" class="button savta-row__remove" data-remove aria-label="' . esc_attr__( 'הסרה', 'savta' ) . '">&#10005;</button></span>';
		echo '</div>';
	};

	echo '<div class="savta-repeater" data-repeater>';
	echo '<div data-rows>';
	foreach ( array_values( $rows ) as $i => $row ) {
		$render_row( $i, (array) $row );
	}
	echo '</div>';
	echo '<template data-row-template>';
	$render_row( '__i__', array() );
	echo '</template>';
	echo '<button type="button" class="button" data-add>+ ' . esc_html__( 'הוספת שורה', 'savta' ) . '</button>';
	echo '</div>';
}

/**
 * Media-library image picker.
 *
 * @param string $key Image key.
 * @param int    $id  Attachment ID.
 * @return void
 */
function savta_render_image_field( string $key, int $id ): void {
	$preview = $id ? wp_get_attachment_image( $id, 'medium', false, array( 'class' => 'savta-image__preview' ) ) : '';
	printf(
		'<div class="savta-image" data-media><input type="hidden" name="savta[%1$s]" value="%2$d" data-media-id><div class="savta-image__frame" data-media-preview>%3$s</div><button type="button" class="button" data-media-pick>%4$s</button> <button type="button" class="button-link savta-image__clear" data-media-clear %6$s>%5$s</button></div>',
		esc_attr( $key ),
		absint( $id ),
		$preview, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() escapes.
		esc_html__( 'בחירת תמונה', 'savta' ),
		esc_html__( 'הסרה (חזרה לתמונת התבנית)', 'savta' ),
		$id ? '' : 'hidden'
	);
}

/**
 * Render a grouped schema as sub-tab panels with form tables.
 *
 * @param array $schema Grouped schema.
 * @param array $values Current values.
 * @return void
 */
function savta_render_schema_panels( array $schema, array $values ): void {
	echo '<div class="savta-panels"><div class="savta-subnav" role="tablist" aria-label="' . esc_attr__( 'סעיפים', 'savta' ) . '">';
	$first = true;
	foreach ( $schema as $slug => $group ) {
		printf( '<button type="button" role="tab" class="savta-subnav__btn %3$s" data-subtab="%1$s" aria-selected="%4$s">%2$s</button>', esc_attr( $slug ), esc_html( $group['label'] ), esc_attr( $first ? 'is-active' : '' ), esc_attr( $first ? 'true' : 'false' ) );
		$first = false;
	}
	echo '</div><div class="savta-panels__body">';
	$first = true;
	foreach ( $schema as $slug => $group ) {
		printf( '<section class="savta-panel %2$s" data-panel="%1$s" role="tabpanel"%3$s><h2>%4$s</h2><table class="form-table" role="presentation"><tbody>', esc_attr( $slug ), esc_attr( $first ? 'is-active' : '' ), $first ? '' : ' hidden', esc_html( $group['label'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute.
		foreach ( $group['fields'] as $key => $field ) {
			echo '<tr><th scope="row"><label for="' . esc_attr( 'savta-' . $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
			savta_render_field( $key, $field, $values[ $key ] ?? ( $field['default'] ?? '' ) );
			echo '</td></tr>';
		}
		echo '</tbody></table></section>';
		$first = false;
	}
	echo '</div></div>';
}

/**
 * Content tab.
 *
 * @return void
 */
function savta_render_content_page(): void {
	savta_admin_header( 'content' );
	echo '<p class="savta-intro">' . esc_html__( 'כל טקסט בעמוד הבית ניתן לעריכה כאן. השינויים נשמרים לכל הלשוניות יחד.', 'savta' ) . '</p>';
	savta_admin_form_open( 'content' );
	savta_render_schema_panels( savta_content_schema(), savta_option_values( SAVTA_OPT_CONTENT ) );
	savta_admin_form_close();
}

/**
 * Settings tab.
 *
 * @return void
 */
function savta_render_settings_page(): void {
	savta_admin_header( 'settings' );
	savta_admin_form_open( 'settings' );
	savta_render_schema_panels( savta_settings_schema(), savta_option_values( SAVTA_OPT_SETTINGS ) );
	savta_admin_form_close();
}

/**
 * Images tab.
 *
 * @return void
 */
function savta_render_images_page(): void {
	savta_admin_header( 'images' );
	echo '<p class="savta-intro">' . esc_html__( 'תמונה שלא נבחרה מציגה את תמונת התבנית. מומלץ להעלות WebP או JPG מכווץ.', 'savta' ) . '</p>';
	savta_admin_form_open( 'images' );
	$values = savta_option_values( SAVTA_OPT_IMAGES );
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( savta_image_schema() as $key => $def ) {
		echo '<tr><th scope="row">' . esc_html( $def['label'] ) . '</th><td>';
		savta_render_image_field( $key, (int) ( $values[ $key ] ?? 0 ) );
		echo '<p class="description">' . esc_html( $def['help'] ) . '</p></td></tr>';
	}
	echo '</tbody></table>';
	savta_admin_form_close();
}
