<?php
/**
 * Accessibility statement page (required by Israeli regulation, ת"י 5568)
 * created once on theme activation, plus a footer link to it.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

const SAVTA_A11Y_PAGE_OPTION = 'savta_accessibility_page_id';

/**
 * Create the statement page if it does not exist yet.
 *
 * @return void
 */
function savta_create_accessibility_page(): void {
	$existing = (int) get_option( SAVTA_A11Y_PAGE_OPTION, 0 );
	if ( $existing && get_post_status( $existing ) ) {
		return;
	}
	$page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => __( 'הצהרת נגישות', 'savta' ),
			'post_name'    => 'accessibility-statement',
			'post_content' => savta_accessibility_statement_content(),
		),
		true
	);
	if ( ! is_wp_error( $page_id ) ) {
		update_option( SAVTA_A11Y_PAGE_OPTION, (int) $page_id, false );
	}
}
add_action( 'after_switch_theme', 'savta_create_accessibility_page' );

/**
 * Self-heal: if the page is missing (theme activated via WP-CLI, multisite,
 * or the page was deleted), recreate it the next time an administrator
 * loads wp-admin. One autoload-free option read on admin requests only.
 *
 * @return void
 */
function savta_ensure_accessibility_page(): void {
	if ( ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) {
		return;
	}
	savta_create_accessibility_page();
}
add_action( 'admin_init', 'savta_ensure_accessibility_page' );

/**
 * URL of the accessibility statement page, or empty string when missing.
 *
 * @return string
 */
function savta_accessibility_page_url(): string {
	$page_id = (int) get_option( SAVTA_A11Y_PAGE_OPTION, 0 );
	if ( ! $page_id || 'publish' !== get_post_status( $page_id ) ) {
		return '';
	}
	return (string) get_permalink( $page_id );
}

/**
 * Default statement text. Square-bracket items are for the site owner to complete.
 *
 * @return string
 */
function savta_accessibility_statement_content(): string {
	$site = get_bloginfo( 'name' );
	$date = wp_date( 'd.m.Y' );

	$sections = array(
		'<p>' . sprintf(
			/* translators: %s: site name */
			esc_html__( 'אנו ב"%s" רואים חשיבות רבה במתן שירות שוויוני לכלל הציבור, ובכלל זה לאנשים עם מוגבלות. האתר הונגש בהתאם לתקנות שוויון זכויות לאנשים עם מוגבלות (התאמות נגישות לשירות), התשע"ג-2013, לתקן הישראלי ת"י 5568 ולהנחיות WCAG 2.2 ברמה AA.', 'savta' ),
			esc_html( $site )
		) . '</p>',
		'<h2>' . esc_html__( 'התאמות הנגישות באתר', 'savta' ) . '</h2>',
		'<ul>'
		. '<li>' . esc_html__( 'ניווט מלא באמצעות מקלדת, כולל קישור "דילוג לתוכן" וסימון פוקוס ברור.', 'savta' ) . '</li>'
		. '<li>' . esc_html__( 'מבנה סמנטי תקין: כותרות בהיררכיה נכונה, אזורי ניווט ותוכן מוגדרים, ותוויות לכל שדות הטופס.', 'savta' ) . '</li>'
		. '<li>' . esc_html__( 'טקסט חלופי לתמונות, וסימון של רכיבים דקורטיביים כך שקוראי מסך ידלגו עליהם.', 'savta' ) . '</li>'
		. '<li>' . esc_html__( 'ניגודיות צבעים העומדת בדרישות רמה AA, וגופן קריא שניתן להגדלה עד 200% ללא פגיעה בפריסה.', 'savta' ) . '</li>'
		. '<li>' . esc_html__( 'כיבוד העדפת המשתמש להפחתת תנועה (prefers-reduced-motion): האנימציות באתר מושבתות אוטומטית.', 'savta' ) . '</li>'
		. '<li>' . esc_html__( 'תאימות לקוראי מסך נפוצים (NVDA, JAWS, VoiceOver, TalkBack) ולדפדפנים המעודכנים.', 'savta' ) . '</li>'
		. '</ul>',
		'<h2>' . esc_html__( 'פנייה בנושא נגישות', 'savta' ) . '</h2>',
		'<p>' . esc_html__( 'אם נתקלתם בקושי או בבעיית נגישות באתר, נשמח לשמוע ולתקן. רכז/ת הנגישות שלנו:', 'savta' ) . '</p>',
		'<ul>'
		. '<li>' . esc_html__( 'שם: [שם רכז/ת הנגישות]', 'savta' ) . '</li>'
		. '<li>' . esc_html__( 'טלפון: [מספר טלפון]', 'savta' ) . '</li>'
		. '<li>' . esc_html__( 'דוא"ל: [כתובת דוא"ל]', 'savta' ) . '</li>'
		. '</ul>',
		'<p>' . sprintf(
			/* translators: %s: date */
			esc_html__( 'ההצהרה עודכנה לאחרונה בתאריך %s.', 'savta' ),
			esc_html( $date )
		) . '</p>',
	);

	return implode( "\n", $sections );
}
