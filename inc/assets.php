<?php
/**
 * Conditional asset loading: one CSS file site-wide, JS on the front page only,
 * fonts preloaded, everything deferred and versioned.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue styles and scripts.
 *
 * @return void
 */
function savta_enqueue_assets(): void {
	$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_style( 'savta-theme', SAVTA_URI . '/assets/css/theme' . $min . '.css', array(), SAVTA_VERSION );

	// Core block styles are not used by this theme: drop them everywhere except when a page uses blocks.
	if ( is_front_page() || is_404() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'classic-theme-styles' );
		wp_dequeue_style( 'global-styles' );
	}

	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_script(
		'savta-behaviors',
		SAVTA_URI . '/assets/js/behaviors' . $min . '.js',
		array(),
		SAVTA_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_add_inline_script(
		'savta-behaviors',
		'window.savtaConfig=' . wp_json_encode( savta_js_config() ) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'savta_enqueue_assets', 20 );

/**
 * Configuration handed to behaviors.js (slot times, day names, endpoints, strings).
 *
 * @return array
 */
function savta_js_config(): array {
	return array(
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'postUrl'  => admin_url( 'admin-post.php' ),
		'action'   => SAVTA_FORM_ACTION,
		'nonce'    => savta_create_nonce(),
		'times'    => savta_slot_times(),
		'days'     => savta_slot_days(),
		'dayCount' => savta_slot_day_count(),
		'taken'    => savta_taken_slots(),
		'blocked'  => savta_slot_blocked_dates(),
		'animate'  => (bool) savta_setting( 'animations' ),
		'i18n'     => array(
			'openCal'     => __( 'לצפייה ביומן', 'savta' ),
			'closeCal'    => __( 'סגירה', 'savta' ),
			'pick'        => __( 'לבדיקת ימים פנויים', 'savta' ),
			'taken'       => __( 'תפוס', 'savta' ),
			/* translators: %s: the chosen appointment slot label. */
			'selected'    => __( 'נבחר מועד: %s', 'savta' ),
			'sending'     => __( 'שולחים…', 'savta' ),
			'submit'      => savta_text( 'form_submit' ),
			'genericErr'  => __( 'משהו השתבש בשליחה. אפשר לנסות שוב, או להתקשר אלינו.', 'savta' ),
			'fixErrors'   => __( 'יש לתקן את השדות המסומנים ולשלוח שוב.', 'savta' ),
			'thanksTitle' => savta_text( 'thanks_title' ),
			'thanksText'  => savta_text( 'thanks_text' ),
		),
	);
}

/**
 * Preload the two heaviest-used font weights and the hero logo (LCP candidates),
 * and set the theme colour for browser chrome.
 *
 * @return void
 */
function savta_preload_head(): void {
	$fonts = array( 'asimon-bold-aaa', 'asimon-black-aaa' );
	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( SAVTA_URI . '/assets/fonts/' . $font . '.woff2' )
		);
	}
	if ( is_front_page() && ! savta_image_id( 'img_logo' ) ) {
		printf(
			'<link rel="preload" href="%s" as="image" type="image/webp" fetchpriority="high">' . "\n",
			esc_url( savta_image_url( 'logo-800.webp' ) )
		);
	}
	echo '<meta name="theme-color" content="#FFF8EF">' . "\n";
}
add_action( 'wp_head', 'savta_preload_head', 1 );

/**
 * Tiny inline script that flips `no-js` to `js` so entrance animations only
 * hide content when JavaScript is available to reveal it again.
 *
 * @return void
 */
function savta_js_class(): void {
	wp_print_inline_script_tag( 'document.documentElement.classList.replace("no-js","js");' );
}
add_action( 'wp_head', 'savta_js_class', 0 );

/**
 * Add `rel="preconnect"` hints are not needed: all assets are same-origin.
 * Add `type="module"`-free defer to any script the theme owns is done above.
 * Remove the jQuery migrate script for visitors when jQuery gets loaded by a plugin.
 *
 * @param WP_Scripts $scripts Scripts registry.
 * @return void
 */
function savta_drop_jquery_migrate( WP_Scripts $scripts ): void {
	if ( is_admin() || ! isset( $scripts->registered['jquery'] ) ) {
		return;
	}
	$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
}
add_action( 'wp_default_scripts', 'savta_drop_jquery_migrate' );
