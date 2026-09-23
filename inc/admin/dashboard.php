<?php
/**
 * Overview page (stats, upcoming meetings, latest leads, quick actions),
 * lead status workflow and CSV export.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

const SAVTA_LEAD_META_STATUS = '_savta_status';

/**
 * Lead statuses.
 *
 * @return array<string, string>
 */
function savta_lead_statuses(): array {
	return array(
		'new'       => __( 'חדשה', 'savta' ),
		'contacted' => __( 'נוצר קשר', 'savta' ),
		'scheduled' => __( 'נקבעה פגישה', 'savta' ),
		'done'      => __( 'הסתיימה', 'savta' ),
		'cancelled' => __( 'בוטלה', 'savta' ),
	);
}

/**
 * Overview page.
 *
 * @return void
 */
function savta_render_overview_page(): void {
	savta_admin_header( 'overview' );

	$counts   = wp_count_posts( SAVTA_LEAD_CPT );
	$total    = (int) ( $counts->publish ?? 0 );
	$week_ids = get_posts(
		array(
			'post_type'              => SAVTA_LEAD_CPT,
			'post_status'            => 'publish',
			'fields'                 => 'ids',
			'numberposts'            => -1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'date_query'             => array( array( 'after' => '7 days ago' ) ),
		)
	);
	$latest   = get_posts(
		array(
			'post_type'              => SAVTA_LEAD_CPT,
			'post_status'            => 'publish',
			'numberposts'            => 6,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		)
	);
	$upcoming = savta_taken_slots();
	sort( $upcoming );
	$statuses = savta_lead_statuses();

	echo '<div class="savta-cards">';
	savta_stat_card( (string) $total, __( 'פניות סה"כ', 'savta' ) );
	savta_stat_card( (string) count( $week_ids ), __( 'פניות בשבוע האחרון', 'savta' ) );
	savta_stat_card( (string) count( $upcoming ), __( 'מועדים תפוסים קדימה', 'savta' ) );
	savta_stat_card( savta_setting( 'calendar_enabled' ) ? __( 'פעיל', 'savta' ) : __( 'כבוי', 'savta' ), __( 'יומן מועדים', 'savta' ) );
	echo '</div>';

	echo '<div class="savta-grid">';

	echo '<div class="card savta-card"><h2>' . esc_html__( 'פעולות מהירות', 'savta' ) . '</h2><p class="savta-quick">';
	$links = array(
		array( home_url( '/' ), __( 'צפייה באתר', 'savta' ) ),
		array( admin_url( 'admin.php?page=savta-content' ), __( 'עריכת טקסטים', 'savta' ) ),
		array( admin_url( 'admin.php?page=savta-images' ), __( 'החלפת תמונות', 'savta' ) ),
		array( admin_url( 'admin.php?page=savta-settings' ), __( 'הגדרות ויומן', 'savta' ) ),
		array( admin_url( 'edit.php?post_type=' . SAVTA_LEAD_CPT ), __( 'כל הפניות', 'savta' ) ),
		array( admin_url( 'customize.php' ), __( 'לוגו ושם האתר', 'savta' ) ),
	);
	$a11y  = (int) get_option( SAVTA_A11Y_PAGE_OPTION, 0 );
	if ( $a11y ) {
		$links[] = array( get_edit_post_link( $a11y, 'raw' ), __( 'הצהרת נגישות', 'savta' ) );
	}
	foreach ( $links as $l ) {
		printf( '<a class="button" href="%1$s">%2$s</a> ', esc_url( (string) $l[0] ), esc_html( $l[1] ) );
	}
	printf( '<a class="button" href="%1$s">%2$s</a> ', esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=savta_export_leads' ), 'savta_export' ) ), esc_html__( 'ייצוא פניות (CSV)', 'savta' ) );
	printf( '<a class="button" href="%1$s">%2$s</a>', esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=savta_purge' ), 'savta_purge' ) ), esc_html__( 'ניקוי מטמון', 'savta' ) );
	echo '</p></div>';

	echo '<div class="card savta-card"><h2>' . esc_html__( 'מועדים תפוסים', 'savta' ) . '</h2>';
	if ( $upcoming ) {
		echo '<ul class="savta-list">';
		foreach ( array_slice( $upcoming, 0, 12 ) as $slot ) {
			echo '<li>' . esc_html( savta_format_slot( $slot ) ) . '</li>';
		}
		echo '</ul>';
	} else {
		echo '<p>' . esc_html__( 'אין מועדים תפוסים כרגע.', 'savta' ) . '</p>';
	}
	echo '</div>';

	echo '<div class="card savta-card savta-card--wide"><h2>' . esc_html__( 'פניות אחרונות', 'savta' ) . '</h2>';
	if ( $latest ) {
		echo '<table class="widefat striped"><thead><tr><th>' . esc_html__( 'שם', 'savta' ) . '</th><th>' . esc_html__( 'טלפון', 'savta' ) . '</th><th>' . esc_html__( 'מועד', 'savta' ) . '</th><th>' . esc_html__( 'סטטוס', 'savta' ) . '</th><th>' . esc_html__( 'התקבל', 'savta' ) . '</th></tr></thead><tbody>';
		foreach ( $latest as $lead ) {
			$status = (string) get_post_meta( $lead->ID, SAVTA_LEAD_META_STATUS, true );
			printf(
				'<tr><td><a href="%1$s">%2$s</a></td><td dir="ltr">%3$s</td><td>%4$s</td><td>%5$s</td><td>%6$s</td></tr>',
				esc_url( (string) get_edit_post_link( $lead->ID, 'raw' ) ),
				esc_html( $lead->post_title ),
				esc_html( (string) get_post_meta( $lead->ID, '_savta_phone', true ) ),
				esc_html( savta_format_slot( (string) get_post_meta( $lead->ID, SAVTA_LEAD_META_SLOT, true ) ) ),
				esc_html( $statuses[ $status ] ?? $statuses['new'] ),
				esc_html( get_the_date( 'd.m.Y H:i', $lead ) )
			);
		}
		echo '</tbody></table>';
	} else {
		echo '<p>' . esc_html__( 'עדיין לא התקבלו פניות.', 'savta' ) . '</p>';
	}
	echo '</div></div></div>';
}

/**
 * Stat tile.
 *
 * @param string $value Big value.
 * @param string $label Caption.
 * @return void
 */
function savta_stat_card( string $value, string $label ): void {
	printf( '<div class="savta-stat"><strong>%1$s</strong><span>%2$s</span></div>', esc_html( $value ), esc_html( $label ) );
}

/**
 * Human-readable slot ("יום שני, 28.9 · 20:00–21:00").
 *
 * @param string $slot Stored slot value.
 * @return string
 */
function savta_format_slot( string $slot ): string {
	if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2}) (.+)$/u', $slot, $m ) ) {
		return '' !== $slot ? $slot : '-';
	}
	$date  = DateTimeImmutable::createFromFormat( '!Y-m-d', "$m[1]-$m[2]-$m[3]", wp_timezone() );
	$names = savta_weekday_names();
	$day   = $date ? ( $names[ (int) $date->format( 'w' ) ] ?? '' ) : '';
	return trim( $day . ', ' . (int) $m[3] . '.' . (int) $m[2] . ' · ' . $m[4] );
}

/* ---------- Lead status ---------- */

/**
 * Status meta box.
 *
 * @return void
 */
function savta_lead_status_box(): void {
	add_meta_box( 'savta-lead-status', __( 'סטטוס טיפול', 'savta' ), 'savta_render_lead_status_box', SAVTA_LEAD_CPT, 'side', 'high' );
}
add_action( 'add_meta_boxes_' . SAVTA_LEAD_CPT, 'savta_lead_status_box' );

/**
 * Render the status select.
 *
 * @param WP_Post $post Lead.
 * @return void
 */
function savta_render_lead_status_box( WP_Post $post ): void {
	$current = (string) get_post_meta( $post->ID, SAVTA_LEAD_META_STATUS, true );
	wp_nonce_field( 'savta_lead_status', 'savta_lead_status_nonce' );
	echo '<select name="savta_status" class="widefat" aria-label="' . esc_attr__( 'סטטוס', 'savta' ) . '">';
	foreach ( savta_lead_statuses() as $key => $label ) {
		printf( '<option value="%1$s" %3$s>%2$s</option>', esc_attr( $key ), esc_html( $label ), selected( '' !== $current ? $current : 'new', $key, false ) );
	}
	echo '</select><p class="description">' . esc_html__( 'פנייה שבוטלה משחררת את המועד שלה ביומן.', 'savta' ) . '</p>';
}

/**
 * Save the status (nonce + capability checked).
 *
 * @param int $post_id Lead ID.
 * @return void
 */
function savta_save_lead_status( int $post_id ): void {
	if ( ! isset( $_POST['savta_lead_status_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['savta_lead_status_nonce'] ) ), 'savta_lead_status' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return;
	}
	$status = isset( $_POST['savta_status'] ) ? sanitize_key( wp_unslash( $_POST['savta_status'] ) ) : 'new';
	if ( ! array_key_exists( $status, savta_lead_statuses() ) ) {
		$status = 'new';
	}
	update_post_meta( $post_id, SAVTA_LEAD_META_STATUS, $status );
	savta_flush_slots_cache();
}
add_action( 'save_post_' . SAVTA_LEAD_CPT, 'savta_save_lead_status' );

/**
 * Status column in the leads list.
 *
 * @param array $columns Columns.
 * @return array
 */
function savta_lead_status_column( array $columns ): array {
	$out = array();
	foreach ( $columns as $key => $label ) {
		$out[ $key ] = $label;
		if ( '_savta_slot' === $key ) {
			$out['_savta_status'] = __( 'סטטוס', 'savta' );
		}
	}
	return $out;
}
add_filter( 'manage_' . SAVTA_LEAD_CPT . '_posts_columns', 'savta_lead_status_column', 20 );

/**
 * Status column value.
 *
 * @param string $column  Column.
 * @param int    $post_id Post ID.
 * @return void
 */
function savta_lead_status_column_value( string $column, int $post_id ): void {
	if ( '_savta_status' !== $column ) {
		return;
	}
	$status = (string) get_post_meta( $post_id, SAVTA_LEAD_META_STATUS, true );
	$labels = savta_lead_statuses();
	printf( '<span class="savta-status savta-status--%1$s">%2$s</span>', esc_attr( '' !== $status ? $status : 'new' ), esc_html( $labels[ $status ] ?? $labels['new'] ) );
}
add_action( 'manage_' . SAVTA_LEAD_CPT . '_posts_custom_column', 'savta_lead_status_column_value', 5, 2 );

/**
 * Status filter dropdown above the leads list.
 *
 * @param string $post_type Current post type.
 * @return void
 */
function savta_lead_status_filter( string $post_type ): void {
	if ( SAVTA_LEAD_CPT !== $post_type ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- list-table filter, read-only.
	$current = isset( $_GET['savta_status'] ) ? sanitize_key( wp_unslash( $_GET['savta_status'] ) ) : '';
	echo '<select name="savta_status" aria-label="' . esc_attr__( 'סינון לפי סטטוס', 'savta' ) . '"><option value="">' . esc_html__( 'כל הסטטוסים', 'savta' ) . '</option>';
	foreach ( savta_lead_statuses() as $key => $label ) {
		printf( '<option value="%1$s" %3$s>%2$s</option>', esc_attr( $key ), esc_html( $label ), selected( $current, $key, false ) );
	}
	echo '</select>';
}
add_action( 'restrict_manage_posts', 'savta_lead_status_filter' );

/**
 * Apply the status filter.
 *
 * @param WP_Query $query Main admin query.
 * @return void
 */
function savta_lead_status_query( WP_Query $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() || SAVTA_LEAD_CPT !== $query->get( 'post_type' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- list-table filter, read-only.
	$status = isset( $_GET['savta_status'] ) ? sanitize_key( wp_unslash( $_GET['savta_status'] ) ) : '';
	if ( '' === $status || ! array_key_exists( $status, savta_lead_statuses() ) ) {
		return;
	}
	$meta = array(
		'key'   => SAVTA_LEAD_META_STATUS,
		'value' => $status,
	);
	if ( 'new' === $status ) {
		$meta = array(
			'relation' => 'OR',
			$meta,
			array(
				'key'     => SAVTA_LEAD_META_STATUS,
				'compare' => 'NOT EXISTS',
			),
		);
	}
	$query->set( 'meta_query', array( $meta ) ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- admin list filter on a small table.
}
add_action( 'pre_get_posts', 'savta_lead_status_query' );

/* ---------- CSV export ---------- */

/**
 * Export all leads as UTF-8 CSV (Excel-friendly BOM, formula-injection safe).
 *
 * @return void
 */
function savta_export_leads(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'אין לך הרשאה לפעולה זו.', 'savta' ), 403 );
	}
	check_admin_referer( 'savta_export' );

	$leads    = get_posts(
		array(
			'post_type'              => SAVTA_LEAD_CPT,
			'post_status'            => array( 'publish', 'trash' ),
			'numberposts'            => -1,
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		)
	);
	$fields   = savta_lead_fields();
	$statuses = savta_lead_statuses();

	nocache_headers();
	header( 'Content-Type: text/csv; charset=UTF-8' );
	header( 'Content-Disposition: attachment; filename="savta-leads-' . wp_date( 'Y-m-d' ) . '.csv"' );
	$out = fopen( 'php://output', 'w' );
	fwrite( $out, "\xEF\xBB\xBF" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite -- streaming CSV.
	$head = array_merge( array( __( 'תאריך', 'savta' ), __( 'שם', 'savta' ) ), array_values( $fields ), array( __( 'סטטוס', 'savta' ), __( 'מצב', 'savta' ) ) );
	fputcsv( $out, $head, ',', '"', '\\' );
	foreach ( $leads as $lead ) {
		$row = array( get_the_date( 'Y-m-d H:i', $lead ), $lead->post_title );
		foreach ( array_keys( $fields ) as $key ) {
			$value = (string) get_post_meta( $lead->ID, $key, true );
			$row[] = '_savta_contact' === $key ? savta_contact_label( $value ) : $value;
		}
		$status = (string) get_post_meta( $lead->ID, SAVTA_LEAD_META_STATUS, true );
		$row[]  = $statuses[ $status ] ?? $statuses['new'];
		$row[]  = 'trash' === $lead->post_status ? __( 'בפח', 'savta' ) : __( 'פעילה', 'savta' );
		fputcsv( $out, array_map( 'savta_csv_cell', $row ), ',', '"', '\\' );
	}
	exit;
}
add_action( 'admin_post_savta_export_leads', 'savta_export_leads' );

/**
 * Neutralise spreadsheet formula injection.
 *
 * @param string $cell Cell value.
 * @return string
 */
function savta_csv_cell( string $cell ): string {
	return preg_match( '/^[=+\-@\t\r]/', $cell ) ? "'" . $cell : $cell;
}

/**
 * "Purge caches" quick action.
 *
 * @return void
 */
function savta_purge_action(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'אין לך הרשאה לפעולה זו.', 'savta' ), 403 );
	}
	check_admin_referer( 'savta_purge' );
	savta_purge_caches();
	wp_safe_redirect(
		add_query_arg(
			array(
				'page'        => 'savta-overview',
				'savta_saved' => 'purged',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_savta_purge', 'savta_purge_action' );
