<?php
/**
 * Lightweight SEO output (description, Open Graph, JSON-LD) that steps aside
 * automatically when a dedicated SEO plugin is active.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether a known SEO plugin will handle meta tags.
 *
 * @return bool
 */
function savta_has_seo_plugin(): bool {
	return defined( 'WPSEO_VERSION' )
		|| class_exists( 'RankMath' )
		|| defined( 'AIOSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'THE_SEO_FRAMEWORK_VERSION' );
}

/**
 * Site description used for meta/OG tags.
 *
 * @return string
 */
function savta_seo_description(): string {
	$default = __( 'שיחה אישית, חינמית ודיסקרטית עם "סבתא" מקשיבה ומנוסה. מיזם חברתי למען הקהילה בהובלת אפרת ברזל. בתיאום מראש בלבד.', 'savta' );
	$custom  = (string) savta_setting( 'seo_description' );
	$tagline = '' !== $custom ? $custom : get_bloginfo( 'description' );
	return (string) apply_filters( 'savta_seo_description', '' !== $tagline ? $tagline : $default );
}

/**
 * Print meta description, Open Graph and Twitter tags.
 *
 * @return void
 */
function savta_meta_tags(): void {
	if ( savta_has_seo_plugin() ) {
		return;
	}
	$title = is_front_page() ? get_bloginfo( 'name' ) : wp_get_document_title();
	$desc  = savta_seo_description();
	$link  = get_permalink();
	$url   = ( is_front_page() || ! $link ) ? home_url( '/' ) : $link;
	$image = savta_og_image();

	$tags = array(
		array( 'name', 'description', $desc ),
		array( 'property', 'og:type', 'website' ),
		array( 'property', 'og:locale', 'he_IL' ),
		array( 'property', 'og:site_name', get_bloginfo( 'name' ) ),
		array( 'property', 'og:title', $title ),
		array( 'property', 'og:description', $desc ),
		array( 'property', 'og:url', $url ),
		array( 'property', 'og:image', $image['url'] ),
		array( 'property', 'og:image:width', (string) $image['w'] ),
		array( 'property', 'og:image:height', (string) $image['h'] ),
		array( 'name', 'twitter:card', 'summary' ),
	);
	foreach ( $tags as $tag ) {
		printf( '<meta %1$s="%2$s" content="%3$s">' . "\n", esc_attr( $tag[0] ), esc_attr( $tag[1] ), esc_attr( $tag[2] ) );
	}
	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
}
add_action( 'wp_head', 'savta_meta_tags', 2 );

/**
 * Open Graph image: dashboard choice, else the bundled logo.
 *
 * @return array{url:string,w:int,h:int}
 */
function savta_og_image(): array {
	$id = savta_image_id( 'og_image' );
	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, 'full' );
		if ( $src ) {
			return array(
				'url' => $src[0],
				'w'   => (int) $src[1],
				'h'   => (int) $src[2],
			);
		}
	}
	return array(
		'url' => savta_image_url( 'logo-800.webp' ),
		'w'   => 800,
		'h'   => 800,
	);
}

/**
 * Google Analytics 4 (only when a valid Measurement ID is set in the dashboard;
 * skipped for logged-in users).
 *
 * @return void
 */
function savta_ga4(): void {
	$id = (string) savta_setting( 'ga4_id' );
	if ( ! preg_match( '/^G-[A-Z0-9]{4,20}$/', $id ) || is_user_logged_in() ) {
		return;
	}
	printf( '<script async src="https://www.googletagmanager.com/gtag/js?id=%s"></script>' . "\n", esc_attr( $id ) ); // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- gtag loader belongs in <head>.
	wp_print_inline_script_tag( 'window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","' . esc_js( $id ) . '",{anonymize_ip:true});' );
}
add_action( 'wp_head', 'savta_ga4', 4 );

/**
 * JSON-LD for the front page: the organisation and the FAQ.
 *
 * @return void
 */
function savta_json_ld(): void {
	if ( ! is_front_page() || savta_has_seo_plugin() ) {
		return;
	}
	$faq_entities = array();
	foreach ( savta_faqs() as $faq ) {
		$faq_entities[] = array(
			'@type'          => 'Question',
			'name'           => $faq['q'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $faq['a'],
			),
		);
	}
	$graph = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type'       => 'NGO',
				'@id'         => home_url( '/#org' ),
				'name'        => get_bloginfo( 'name' ),
				'url'         => home_url( '/' ),
				'logo'        => savta_og_image()['url'],
				'description' => savta_seo_description(),
				'areaServed'  => __( 'בני ברק', 'savta' ),
				'founder'     => array(
					'@type' => 'Person',
					'name'  => __( 'אפרת ברזל', 'savta' ),
				),
			),
			array(
				'@type'      => 'WebSite',
				'@id'        => home_url( '/#website' ),
				'url'        => home_url( '/' ),
				'name'       => get_bloginfo( 'name' ),
				'inLanguage' => 'he-IL',
				'publisher'  => array( '@id' => home_url( '/#org' ) ),
			),
			array(
				'@type'      => 'FAQPage',
				'@id'        => home_url( '/#faq' ),
				'mainEntity' => $faq_entities,
			),
		),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $graph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'savta_json_ld', 3 );
