<?php
/**
 * Small output helpers shared by the template parts.
 * Every helper escapes its own output.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print an `<img>` for a registered theme image.
 *
 * @param string $key   Key in savta_images().
 * @param array  $attrs Extra attributes (class, loading, sizes, srcset…).
 * @return void
 */
function savta_the_image( string $key, array $attrs = array() ): void {
	$images = savta_images();
	if ( empty( $images[ $key ] ) ) {
		return;
	}
	$img   = $images[ $key ];
	$base  = array(
		'src'      => savta_image_url( $img['file'] ),
		'width'    => $img['w'],
		'height'   => $img['h'],
		'alt'      => $img['alt'],
		'loading'  => 'lazy',
		'decoding' => 'async',
	);
	$attrs = array_merge( $base, $attrs );

	echo '<img';
	foreach ( $attrs as $name => $value ) {
		if ( false === $value || null === $value ) {
			continue;
		}
		if ( true === $value ) {
			echo ' ' . esc_attr( $name );
			continue;
		}
		$escaped = 'src' === $name ? esc_url( (string) $value ) : esc_attr( (string) $value );
		echo ' ' . esc_attr( $name ) . '="' . $escaped . '"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	}
	echo '>';
}

/**
 * Print a decorative image "window" (framed picture with a distinctive shape).
 *
 * @param string $key   Image key.
 * @param string $shape One of: arch, arch-down, circle, arch-sm, side, leaf.
 * @param array  $attrs Extra img attributes.
 * @return void
 */
function savta_the_window( string $key, string $shape, array $attrs = array() ): void {
	echo '<div class="window window--' . esc_attr( $shape ) . '" data-window>';
	savta_the_image( $key, array_merge( array( 'class' => 'window__img' ), $attrs ) );
	echo '</div>';
}

/**
 * Small heart SVG path (used for petals and card marks).
 *
 * @return string
 */
function savta_heart_path(): string {
	return 'M12 21 C12 21 2 13 2 7.5 C2 4 4.6 2 7.2 2 C9.3 2 11 3.6 12 5.4 C13 3.6 14.7 2 16.8 2 C19.4 2 22 4 22 7.5 C22 13 12 21 12 21 Z';
}

/**
 * Leaf/petal SVG path.
 *
 * @return string
 */
function savta_petal_path(): string {
	return 'M1 15 C7 3 20 1 25 3 C22 14 9 18 1 15 Z';
}

/**
 * Print one falling heart or petal. Values come verbatim from the design source.
 *
 * @param array $p Petal definition: type ('heart'|'petal'), right (CSS offset), anim (animation tail), w, h (px), fill, opacity.
 * @return void
 */
function savta_the_petal( array $p ): void {
	$is_petal = 'petal' === $p['type'];
	$viewbox  = $is_petal ? '0 0 26 18' : '0 0 24 22';
	$path     = $is_petal ? savta_petal_path() : savta_heart_path();
	printf(
		'<span class="petal" style="right:%1$s;animation:petalFall %2$s"><svg width="%3$d" height="%4$d" viewBox="%5$s" fill="%6$s" opacity="%7$s" aria-hidden="true" focusable="false"><path d="%8$s"/></svg></span>',
		esc_attr( $p['right'] ),
		esc_attr( $p['anim'] ),
		(int) $p['w'],
		(int) $p['h'],
		esc_attr( $viewbox ),
		esc_attr( $p['fill'] ),
		esc_attr( $p['opacity'] ),
		esc_attr( $path )
	);
}

/**
 * Print one flying bird outline.
 *
 * @param array $b { top, right, anim, w, h, stroke_width }.
 * @return void
 */
function savta_the_bird( array $b ): void {
	printf(
		'<span class="bird" style="top:%1$s;right:%2$s;animation:birdFly %3$s"><svg width="%4$d" height="%5$d" viewBox="0 0 32 14" fill="none" stroke="#C98573" stroke-width="%6$s" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M1 9 C5 3 9 3 13 8"/><path d="M13 8 C17 2 22 2 26 8"/></svg></span>',
		esc_attr( $b['top'] ),
		esc_attr( $b['right'] ),
		esc_attr( $b['anim'] ),
		(int) $b['w'],
		(int) $b['h'],
		esc_attr( $b['stroke_width'] )
	);
}

/**
 * Print the section number eyebrow ("01" … "08").
 *
 * @param string $number Two-digit number.
 * @param string $modifier Extra class for colour variants.
 * @return void
 */
function savta_the_section_number( string $number, string $modifier = '' ): void {
	printf(
		'<p class="section-num %1$s" aria-hidden="true">%2$s</p>',
		esc_attr( $modifier ),
		esc_html( $number )
	);
}

/**
 * Print the site logo link for the sticky nav.
 * Uses the Customizer logo when one is set, else the bundled logo.
 *
 * @return void
 */
function savta_the_nav_logo(): void {
	echo '<a class="site-nav__logo" href="#top" aria-label="' . esc_attr__( 'סבתא על הספסל – לראש העמוד', 'savta' ) . '">';
	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		echo wp_get_attachment_image(
			$logo_id,
			array( 168, 168 ),
			false,
			array(
				'class'   => 'site-nav__img',
				'alt'     => '',
				'loading' => 'eager',
			)
		);
	} else {
		savta_the_image(
			'logo-small',
			array(
				'class'   => 'site-nav__img',
				'alt'     => '',
				'loading' => 'eager',
			)
		);
	}
	echo '</a>';
}
