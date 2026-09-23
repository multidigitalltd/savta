<?php
/**
 * Appointment slots: configuration, validation and "already booked" lookup.
 *
 * Slot value format (stored and submitted): `YYYY-MM-DD HH:MM–HH:MM`, e.g. `2026-10-05 20:00–21:00`.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

const SAVTA_TAKEN_SLOTS_TRANSIENT = 'savta_taken_slots';

/**
 * Time ranges offered on each meeting day (60-minute meetings, 10-minute break).
 *
 * @return string[]
 */
function savta_slot_times(): array {
	return apply_filters( 'savta_slot_times', array( '20:00–21:00', '21:10–22:10' ) );
}

/**
 * Meeting weekdays keyed by JS/PHP weekday number (0 = Sunday) => Hebrew label.
 *
 * @return array<int, string>
 */
function savta_slot_days(): array {
	return apply_filters(
		'savta_slot_days',
		array(
			1 => __( 'יום שני', 'savta' ),
			2 => __( 'יום שלישי', 'savta' ),
		)
	);
}

/**
 * How many upcoming meeting days the picker shows.
 *
 * @return int
 */
function savta_slot_day_count(): int {
	return (int) apply_filters( 'savta_slot_day_count', 4 );
}

/**
 * How far ahead (days) a submitted slot may be.
 *
 * @return int
 */
function savta_slot_horizon_days(): int {
	return (int) apply_filters( 'savta_slot_horizon_days', 60 );
}

/**
 * Validate a submitted slot string against the configured days/times and horizon.
 *
 * @param string $slot Raw slot value.
 * @return bool
 */
function savta_is_valid_slot( string $slot ): bool {
	if ( ! preg_match( '/^(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}–\d{2}:\d{2})$/u', $slot, $m ) ) {
		return false;
	}
	if ( ! in_array( $m[2], savta_slot_times(), true ) ) {
		return false;
	}
	$tz   = wp_timezone();
	$date = DateTimeImmutable::createFromFormat( '!Y-m-d', $m[1], $tz );
	if ( ! $date || $date->format( 'Y-m-d' ) !== $m[1] ) {
		return false;
	}
	if ( ! array_key_exists( (int) $date->format( 'w' ), savta_slot_days() ) ) {
		return false;
	}
	$today = new DateTimeImmutable( 'today', $tz );
	$diff  = (int) $today->diff( $date )->format( '%r%a' );
	return $diff >= 1 && $diff <= savta_slot_horizon_days();
}

/**
 * Slots already taken by an existing (non-trashed) lead, from today onwards.
 * Cached in a transient; invalidated whenever a lead changes.
 *
 * @return string[]
 */
function savta_taken_slots(): array {
	$cached = get_transient( SAVTA_TAKEN_SLOTS_TRANSIENT );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	global $wpdb;
	$today = wp_date( 'Y-m-d' );
	// Single prepared query, only the column we need.
	$slots = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- result is cached in a transient below.
		$wpdb->prepare(
			"SELECT pm.meta_value FROM {$wpdb->postmeta} pm
			 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			 WHERE pm.meta_key = %s AND p.post_type = %s AND p.post_status = %s AND pm.meta_value >= %s",
			SAVTA_LEAD_META_SLOT,
			SAVTA_LEAD_CPT,
			'publish',
			$today
		)
	);
	$slots = array_values( array_unique( array_map( 'strval', (array) $slots ) ) );
	set_transient( SAVTA_TAKEN_SLOTS_TRANSIENT, $slots, 10 * MINUTE_IN_SECONDS );
	return $slots;
}

/**
 * Whether a slot is already booked.
 *
 * @param string $slot Slot value.
 * @return bool
 */
function savta_is_slot_taken( string $slot ): bool {
	return in_array( $slot, savta_taken_slots(), true );
}

/**
 * Drop the taken-slots cache and ask page caches to refresh the front page.
 *
 * @return void
 */
function savta_flush_slots_cache(): void {
	delete_transient( SAVTA_TAKEN_SLOTS_TRANSIENT );
	do_action( 'litespeed_purge_url', home_url( '/' ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- LiteSpeed Cache API.
	if ( function_exists( 'rocket_clean_home' ) ) {
		rocket_clean_home();
	}
}
