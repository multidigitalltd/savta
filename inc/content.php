<?php
/**
 * Site copy and data. Everything is filterable so a client-editing layer
 * (ACF, Customizer, options) can override any list without touching templates.
 *
 * Copy is used verbatim from the design handoff, including quotation marks and em-dashes.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

/**
 * Navigation anchors (fallback when no menu is assigned to `primary`).
 *
 * @return array<int, array{href:string,label:string}>
 */
function savta_nav_links(): array {
	return apply_filters( 'savta_nav_links', savta_list( 'nav_links' ) );
}

/**
 * "How it works" steps.
 *
 * @return array<int, array{title:string,text:string}>
 */
function savta_steps(): array {
	return apply_filters( 'savta_steps', savta_list( 'how_steps' ) );
}

/**
 * "Who is it for" checklist.
 *
 * @return string[]
 */
function savta_audience(): array {
	return apply_filters( 'savta_audience', savta_list( 'who_items' ) );
}

/**
 * Quote cards.
 *
 * @return string[]
 */
function savta_quotes(): array {
	return apply_filters( 'savta_quotes', savta_list( 'quotes_items' ) );
}

/**
 * FAQ entries.
 *
 * @return array<int, array{q:string,a:string}>
 */
function savta_faqs(): array {
	return apply_filters( 'savta_faqs', savta_list( 'faq_items' ) );
}

/**
 * Theme image registry: bundled file, intrinsic size, alt text and the dashboard
 * image slot that can override it with a media-library attachment.
 * Sizes are real pixel dimensions so `<img width height>` reserves space (zero CLS).
 *
 * @return array<string, array{file:string,w:int,h:int,alt:string}>
 */
function savta_images(): array {
	return apply_filters(
		'savta_images',
		array(
			'logo'       => array(
				'slot' => 'img_logo',
				'file' => 'logo-800.webp',
				'w'    => 800,
				'h'    => 800,
				'alt'  => __( 'סבתא על הספסל: איור של סבתא יושבת על ספסל בגן', 'savta' ),
			),
			'logo-small' => array(
				'slot' => 'img_logo',
				'file' => 'logo-168.webp',
				'w'    => 168,
				'h'    => 168,
				'alt'  => __( 'סבתא על הספסל', 'savta' ),
			),
			'intro'      => array(
				'slot' => 'img_intro',
				'file' => 'bench-garden.webp',
				'w'    => 890,
				'h'    => 1112,
				'alt'  => __( 'איור: ספסל בגן, אור אחר צהריים', 'savta' ),
			),
			'about'      => array(
				'slot' => 'img_about',
				'file' => 'two-women-bench.webp',
				'w'    => 728,
				'h'    => 485,
				'alt'  => __( 'איור: שתי נשים יושבות בשיחה על ספסל', 'savta' ),
			),
			'who'        => array(
				'slot' => 'img_who',
				'file' => 'tea-hands.webp',
				'w'    => 676,
				'h'    => 676,
				'alt'  => __( 'איור: שתי ידיים עם כוס תה', 'savta' ),
			),
			'efrat'      => array(
				'slot' => 'img_efrat',
				'file' => 'efrat.webp',
				'w'    => 480,
				'h'    => 604,
				'alt'  => savta_text( 'efrat_alt' ),
			),
			'signup'     => array(
				'slot' => 'img_signup',
				'file' => 'empty-bench.webp',
				'w'    => 756,
				'h'    => 504,
				'alt'  => __( 'איור: ספסל ריק ממתין בגן', 'savta' ),
			),
		)
	);
}

/**
 * Absolute URL of a theme image file.
 *
 * @param string $file File name inside assets/images.
 * @return string
 */
function savta_image_url( string $file ): string {
	return SAVTA_URI . '/assets/images/' . $file;
}
