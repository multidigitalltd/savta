<?php
/**
 * Build script: writes minified copies of the theme CSS and JS.
 * Usage: php bin/build.php
 *
 * Deliberately dependency-free (no Node toolchain required on the server).
 * The minifiers are conservative: they strip comments and collapse whitespace.
 *
 * @package Savta
 */

declare(strict_types=1);

$root = dirname( __DIR__ );

/**
 * Minify CSS: strip comments (keeps /*! banners), collapse whitespace, drop last semicolons.
 *
 * @param string $css Source CSS.
 * @return string
 */
function savta_minify_css( string $css ): string {
	$banner = '';
	if ( preg_match( '#^/\*!.*?\*/#s', $css, $m ) ) {
		$banner = $m[0] . "\n";
	}
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );
	$css = preg_replace( '/\s+/', ' ', $css );
	$css = preg_replace( '/\s*([{};:,>])\s*/', '$1', $css );
	$css = str_replace( ';}', '}', $css );
	return $banner . trim( $css );
}

/**
 * Minify JS conservatively: strip block comments and leading indentation / blank lines.
 * (No renaming, no ASI games: safe for any browser and for LiteSpeed/WP Rocket re-minification.)
 *
 * @param string $js Source JS.
 * @return string
 */
function savta_minify_js( string $js ): string {
	$banner = '';
	if ( preg_match( '#^/\*!.*?\*/#s', $js, $m ) ) {
		$banner = "/*! Savta al HaSafsal behaviours. Source: assets/js/behaviors.js */\n";
	}
	$js    = preg_replace( '#/\*(?!!).*?\*/#s', '', $js );
	$lines = array();
	foreach ( explode( "\n", $js ) as $line ) {
		$line = trim( $line );
		if ( '' === $line || str_starts_with( $line, '/*!' ) || str_starts_with( $line, '* ' ) || '*/' === $line || '*' === $line ) {
			continue;
		}
		$lines[] = $line;
	}
	return $banner . implode( "\n", $lines );
}

$targets = array(
	'assets/css/theme.css'    => array( 'assets/css/theme.min.css', 'savta_minify_css' ),
	'assets/js/behaviors.js'  => array( 'assets/js/behaviors.min.js', 'savta_minify_js' ),
);

foreach ( $targets as $src => [ $dest, $fn ] ) {
	$in  = file_get_contents( $root . '/' . $src );
	$out = $fn( $in );
	file_put_contents( $root . '/' . $dest, $out . "\n" );
	printf( "%s -> %s (%d -> %d bytes)\n", $src, $dest, strlen( $in ), strlen( $out ) );
}
