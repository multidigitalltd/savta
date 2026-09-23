<?php
/**
 * Theme setup: supports, menus, head clean-up and cache friendliness.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and the (optional) primary menu location.
 *
 * @return void
 */
function savta_setup(): void {
	load_theme_textdomain( 'savta', SAVTA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'width'       => 168,
			'height'      => 168,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	register_nav_menus(
		array( 'primary' => __( 'תפריט ראשי (קישורי עוגן)', 'savta' ) )
	);

	// The site is Hebrew-only by design; keep the editor width sane for the statement page.
	add_editor_style( 'assets/css/theme.min.css' );
}
add_action( 'after_setup_theme', 'savta_setup' );

/**
 * Body classes for dashboard switches (animations off / steam off).
 *
 * @param array $classes Body classes.
 * @return array
 */
function savta_body_classes( array $classes ): array {
	if ( ! savta_setting( 'animations' ) ) {
		$classes[] = 'no-anim';
	}
	if ( ! savta_setting( 'steam' ) ) {
		$classes[] = 'no-steam';
	}
	return $classes;
}
add_filter( 'body_class', 'savta_body_classes' );

/**
 * Remove head output the site never uses (fewer bytes, fewer requests).
 *
 * @return void
 */
function savta_clean_head(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'the_generator', '__return_empty_string' );
}
add_action( 'init', 'savta_clean_head' );

/**
 * Disable XML-RPC: nothing on this site needs it and it is a brute-force vector.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove the REST user enumeration endpoint for visitors.
 *
 * @param array $endpoints Registered REST endpoints.
 * @return array
 */
function savta_restrict_user_endpoints( array $endpoints ): array {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}
	unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	return $endpoints;
}
add_filter( 'rest_endpoints', 'savta_restrict_user_endpoints' );

/**
 * Add lazy loading + async decoding defaults are handled by core; make sure
 * the theme's own `<img>` tags get `decoding="async"` as well.
 *
 * @param array $attr Image attributes.
 * @return array
 */
function savta_image_attrs( array $attr ): array {
	$attr['decoding'] = $attr['decoding'] ?? 'async';
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'savta_image_attrs' );

/**
 * Tell page caches not to store the post-submit state of the front page
 * (`?savta=sent|error`). Works with LiteSpeed Cache, WP Rocket and generic
 * `Cache-Control` aware proxies such as Cloudflare.
 *
 * @return void
 */
function savta_no_cache_after_submit(): void {
	if ( ! is_front_page() || ! savta_form_state() ) {
		return;
	}
	nocache_headers();
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound -- WP Rocket / W3TC convention.
	}
	do_action( 'litespeed_control_set_nocache', 'savta form state' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- LiteSpeed Cache API.
}
add_action( 'template_redirect', 'savta_no_cache_after_submit' );

/**
 * Register the `savta` query var used for the no-JS form round-trip.
 *
 * @param array $vars Public query vars.
 * @return array
 */
function savta_query_vars( array $vars ): array {
	$vars[] = 'savta';
	return $vars;
}
add_filter( 'query_vars', 'savta_query_vars' );

/**
 * Current no-JS form state from the URL: 'sent', 'error' or '' (none).
 *
 * @return string
 */
function savta_form_state(): string {
	$state = get_query_var( 'savta', '' );
	return in_array( $state, array( 'sent', 'error' ), true ) ? $state : '';
}

/**
 * The site is Hebrew-only: guarantee `lang="he…"` and `dir="rtl"` on <html>
 * even before the Hebrew language pack is installed. Filterable for other locales.
 *
 * @param string $output Output of language_attributes().
 * @return string
 */
function savta_language_attributes( string $output ): string {
	if ( ! apply_filters( 'savta_force_hebrew', true ) ) {
		return $output;
	}
	if ( ! str_contains( $output, 'lang="he' ) ) {
		$output = (string) preg_replace( '/lang="[^"]*"/', 'lang="he"', $output );
		if ( ! str_contains( $output, 'lang=' ) ) {
			$output = trim( 'lang="he" ' . $output );
		}
	}
	if ( ! str_contains( $output, 'dir=' ) ) {
		$output = 'dir="rtl" ' . $output;
	}
	return $output;
}
add_filter( 'language_attributes', 'savta_language_attributes' );

/**
 * The front page and 404 use no blocks: skip core's global-styles / classic
 * block CSS entirely (saves an inline stylesheet and an HTTP request).
 *
 * @return void
 */
function savta_drop_block_styles(): void {
	if ( ! is_front_page() && ! is_404() ) {
		return;
	}
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
}
add_action( 'wp', 'savta_drop_block_styles' );
