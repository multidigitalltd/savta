<?php
/**
 * Booking form handler (admin-post.php, works with and without JavaScript).
 *
 * Security: nonce (long-lived so cached pages keep working, refreshed via
 * admin-ajax by JS), honeypot, per-IP rate limit, full server-side validation,
 * sanitization of every field and escaping of every output.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

const SAVTA_FORM_ACTION = 'savta_lead';
const SAVTA_NONCE_FIELD = 'savta_nonce';

/**
 * Rate limit: max submissions per IP per window.
 *
 * @return array{limit:int,window:int}
 */
function savta_rate_limit(): array {
	return (array) apply_filters(
		'savta_rate_limit',
		array(
			'limit'  => 5,
			'window' => HOUR_IN_SECONDS,
		)
	);
}

/**
 * Nonce lifetime for the public form. Front pages are served from full-page
 * caches for up to a week, so the embedded nonce must outlive the cache.
 *
 * @return int
 */
function savta_nonce_life(): int {
	return WEEK_IN_SECONDS;
}

/**
 * Create the form nonce with the extended lifetime.
 *
 * @return string
 */
function savta_create_nonce(): string {
	add_filter( 'nonce_life', 'savta_nonce_life' );
	$nonce = wp_create_nonce( SAVTA_FORM_ACTION );
	remove_filter( 'nonce_life', 'savta_nonce_life' );
	return $nonce;
}

/**
 * Verify the form nonce with the extended lifetime.
 *
 * @param string $nonce Nonce value.
 * @return bool
 */
function savta_verify_nonce( string $nonce ): bool {
	add_filter( 'nonce_life', 'savta_nonce_life' );
	$ok = (bool) wp_verify_nonce( $nonce, SAVTA_FORM_ACTION );
	remove_filter( 'nonce_life', 'savta_nonce_life' );
	return $ok;
}

/**
 * Admin-ajax: hand a fresh nonce to the JavaScript submit path.
 * admin-ajax responses are never page-cached.
 *
 * @return void
 */
function savta_ajax_nonce(): void {
	nocache_headers();
	wp_send_json_success( array( 'nonce' => savta_create_nonce() ) );
}
add_action( 'wp_ajax_savta_nonce', 'savta_ajax_nonce' );
add_action( 'wp_ajax_nopriv_savta_nonce', 'savta_ajax_nonce' );

add_action( 'admin_post_' . SAVTA_FORM_ACTION, 'savta_handle_lead' );
add_action( 'admin_post_nopriv_' . SAVTA_FORM_ACTION, 'savta_handle_lead' );

/**
 * Client IP for rate limiting. Behind Cloudflare with the official plugin or
 * mod_remoteip, REMOTE_ADDR already holds the visitor address.
 *
 * @return string
 */
function savta_client_ip(): string {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
	return (string) apply_filters( 'savta_client_ip', $ip );
}

/**
 * Count a submission attempt for this IP; returns true when the limit is exceeded.
 *
 * @return bool
 */
function savta_is_rate_limited(): bool {
	$rl    = savta_rate_limit();
	$key   = 'savta_rl_' . hash( 'sha256', savta_client_ip() . wp_salt( 'nonce' ) );
	$count = (int) get_transient( $key );
	if ( $count >= (int) $rl['limit'] ) {
		return true;
	}
	set_transient( $key, $count + 1, (int) $rl['window'] );
	return false;
}

/**
 * Whether the request came from behaviors.js (expects JSON) or a plain form post.
 *
 * @return bool
 */
function savta_wants_json(): bool {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- only decides the response format; nonce is verified in the handler.
	return ! empty( $_POST['savta_ajax'] );
}

/**
 * Respond with an error (JSON or redirect back to the form).
 *
 * @param string $message Human message.
 * @param array  $errors  Field => message map.
 * @param int    $status  HTTP status for the JSON path.
 * @return never
 */
function savta_respond_error( string $message, array $errors = array(), int $status = 400 ): never {
	if ( savta_wants_json() ) {
		wp_send_json_error(
			array(
				'message' => $message,
				'errors'  => $errors,
			),
			$status
		);
	}
	wp_safe_redirect( add_query_arg( 'savta', 'error', home_url( '/' ) ) . '#signup' );
	exit;
}

/**
 * Normalise a phone number: strip separators, convert +972 to a leading 0.
 *
 * @param string $raw Raw input.
 * @return string
 */
function savta_normalize_phone( string $raw ): string {
	$digits = preg_replace( '/[\s\-\.\(\)]/u', '', $raw ) ?? '';
	if ( str_starts_with( $digits, '+972' ) ) {
		$digits = '0' . substr( $digits, 4 );
	} elseif ( str_starts_with( $digits, '972' ) ) {
		$digits = '0' . substr( $digits, 3 );
	}
	return $digits;
}

/**
 * Validate and sanitise the posted fields.
 *
 * @return array{data:array<string,string>,errors:array<string,string>}
 */
function savta_validate_submission(): array {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- nonce verified by the caller (savta_handle_lead).
	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? savta_normalize_phone( sanitize_text_field( wp_unslash( $_POST['phone'] ) ) ) : '';
	$city    = isset( $_POST['city'] ) ? sanitize_text_field( wp_unslash( $_POST['city'] ) ) : '';
	$age_raw = isset( $_POST['age'] ) ? sanitize_text_field( wp_unslash( $_POST['age'] ) ) : '';
	$contact = isset( $_POST['contact'] ) ? sanitize_key( wp_unslash( $_POST['contact'] ) ) : 'phone';
	$slot    = isset( $_POST['slot'] ) ? sanitize_text_field( wp_unslash( $_POST['slot'] ) ) : '';
	$topic   = isset( $_POST['topic'] ) ? sanitize_textarea_field( wp_unslash( $_POST['topic'] ) ) : '';
	$consent = ! empty( $_POST['consent'] );
	// phpcs:enable

	$errors = array();

	if ( mb_strlen( $name ) < 2 || mb_strlen( $name ) > 60 ) {
		$errors['name'] = __( 'נא להזין שם (2 עד 60 תווים).', 'savta' );
	}
	if ( ! preg_match( '/^0\d{8,9}$/', $phone ) ) {
		$errors['phone'] = __( 'נא להזין מספר טלפון ישראלי תקין.', 'savta' );
	}
	if ( mb_strlen( $city ) > 60 ) {
		$errors['city'] = __( 'שם העיר ארוך מדי.', 'savta' );
	}
	$age = '';
	if ( '' !== $age_raw ) {
		$age_int = absint( $age_raw );
		if ( $age_int < 16 || $age_int > 120 ) {
			$errors['age'] = __( 'נא להזין גיל בין 16 ל-120.', 'savta' );
		} else {
			$age = (string) $age_int;
		}
	}
	if ( ! in_array( $contact, array( 'phone', 'whatsapp' ), true ) ) {
		$contact = 'phone';
	}
	if ( '' !== $slot ) {
		if ( ! savta_is_valid_slot( $slot ) ) {
			$errors['slot'] = __( 'המועד שנבחר אינו תקין. נא לבחור מועד מהיומן.', 'savta' );
		} elseif ( savta_is_slot_taken( $slot ) ) {
			$errors['slot'] = __( 'המועד הזה נתפס בינתיים. נא לבחור מועד אחר.', 'savta' );
		}
	}
	if ( mb_strlen( $topic ) > 1500 ) {
		$errors['topic'] = __( 'הטקסט ארוך מדי (עד 1500 תווים).', 'savta' );
	}
	if ( ! $consent ) {
		$errors['consent'] = __( 'יש לאשר שמדובר בשיחת הקשבה קהילתית.', 'savta' );
	}

	return array(
		'data'   => compact( 'name', 'phone', 'city', 'age', 'contact', 'slot', 'topic' ),
		'errors' => $errors,
	);
}

/**
 * Main handler for the booking form.
 *
 * @return void
 */
function savta_handle_lead(): void {
	nocache_headers();

	$nonce = isset( $_POST[ SAVTA_NONCE_FIELD ] ) ? sanitize_text_field( wp_unslash( $_POST[ SAVTA_NONCE_FIELD ] ) ) : '';
	if ( ! savta_verify_nonce( $nonce ) ) {
		savta_respond_error( __( 'פג תוקף הטופס. נא לרענן את העמוד ולנסות שוב.', 'savta' ), array(), 403 );
	}

	// Honeypot: real users never see or fill this field.
	if ( ! empty( $_POST['website'] ) ) {
		// Pretend success so bots learn nothing.
		savta_respond_success();
	}

	if ( savta_is_rate_limited() ) {
		savta_respond_error( __( 'נשלחו יותר מדי פניות בזמן קצר. נא לנסות שוב מאוחר יותר.', 'savta' ), array(), 429 );
	}

	$validated = savta_validate_submission();
	if ( ! empty( $validated['errors'] ) ) {
		savta_respond_error( __( 'יש לתקן את השדות המסומנים ולשלוח שוב.', 'savta' ), $validated['errors'], 422 );
	}

	$lead_id = savta_save_lead( $validated['data'] );
	if ( is_wp_error( $lead_id ) ) {
		savta_respond_error( __( 'משהו השתבש בשמירה. נא לנסות שוב.', 'savta' ), array(), 500 );
	}

	savta_notify_lead( $lead_id, $validated['data'] );
	do_action( 'savta_lead_created', $lead_id, $validated['data'] );

	savta_respond_success();
}

/**
 * Persist the lead as a `savta_lead` post.
 *
 * @param array<string,string> $data Validated data.
 * @return int|WP_Error
 */
function savta_save_lead( array $data ): int|WP_Error {
	$lead_id = wp_insert_post(
		array(
			'post_type'   => SAVTA_LEAD_CPT,
			'post_status' => 'publish',
			'post_title'  => $data['name'],
		),
		true
	);
	if ( is_wp_error( $lead_id ) ) {
		return $lead_id;
	}
	$meta = array(
		'_savta_phone'   => $data['phone'],
		'_savta_city'    => $data['city'],
		'_savta_age'     => $data['age'],
		'_savta_contact' => $data['contact'],
		'_savta_slot'    => $data['slot'],
		'_savta_topic'   => $data['topic'],
	);
	foreach ( $meta as $key => $value ) {
		if ( '' !== $value ) {
			add_post_meta( $lead_id, $key, $value, true );
		}
	}
	savta_flush_slots_cache();
	return (int) $lead_id;
}

/**
 * Email the site owner about a new lead (plain text, no HTML).
 *
 * @param int                  $lead_id Lead post ID.
 * @param array<string,string> $data    Validated data.
 * @return void
 */
function savta_notify_lead( int $lead_id, array $data ): void {
	$lines   = array(
		__( 'התקבלה פנייה חדשה באתר "סבתא על הספסל".', 'savta' ),
		'',
		__( 'שם:', 'savta' ) . ' ' . $data['name'],
		__( 'טלפון:', 'savta' ) . ' ' . $data['phone'],
		__( 'עיר:', 'savta' ) . ' ' . ( '' !== $data['city'] ? $data['city'] : '-' ),
		__( 'גיל:', 'savta' ) . ' ' . ( '' !== $data['age'] ? $data['age'] : '-' ),
		__( 'דרך יצירת קשר:', 'savta' ) . ' ' . savta_contact_label( $data['contact'] ),
		__( 'מועד מבוקש:', 'savta' ) . ' ' . ( '' !== $data['slot'] ? $data['slot'] : '-' ),
		__( 'נושא:', 'savta' ),
		'' !== $data['topic'] ? $data['topic'] : '-',
		'',
		__( 'לצפייה בפנייה:', 'savta' ) . ' ' . admin_url( 'post.php?post=' . $lead_id . '&action=edit' ),
	);
	$subject = sprintf(
		/* translators: %s: lead name */
		__( 'פנייה חדשה: %s', 'savta' ),
		$data['name']
	);
	wp_mail(
		savta_lead_recipients(),
		wp_specialchars_decode( $subject ),
		implode( "\n", $lines ),
		array( 'Content-Type: text/plain; charset=UTF-8' )
	);
}

/**
 * Success response (JSON or redirect to the thank-you state).
 *
 * @return never
 */
function savta_respond_success(): never {
	if ( savta_wants_json() ) {
		wp_send_json_success( array( 'message' => __( 'תודה, הפרטים התקבלו.', 'savta' ) ) );
	}
	wp_safe_redirect( add_query_arg( 'savta', 'sent', home_url( '/' ) ) . '#signup' );
	exit;
}
