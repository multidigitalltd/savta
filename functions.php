<?php
/**
 * Savta al HaSafsal theme bootstrap.
 *
 * Loads the small, focused modules under /inc. No page builders, no jQuery,
 * no third-party libraries: WordPress core + vanilla JS only.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

define( 'SAVTA_VERSION', '1.1.0' );
define( 'SAVTA_DIR', get_template_directory() );
define( 'SAVTA_URI', get_template_directory_uri() );

require SAVTA_DIR . '/inc/admin/schema.php';
require SAVTA_DIR . '/inc/admin/options.php';
require SAVTA_DIR . '/inc/setup.php';
require SAVTA_DIR . '/inc/assets.php';
require SAVTA_DIR . '/inc/content.php';
require SAVTA_DIR . '/inc/template-tags.php';
require SAVTA_DIR . '/inc/slots.php';
require SAVTA_DIR . '/inc/leads.php';
require SAVTA_DIR . '/inc/form.php';
require SAVTA_DIR . '/inc/seo.php';
require SAVTA_DIR . '/inc/accessibility.php';

if ( is_admin() ) {
	require SAVTA_DIR . '/inc/admin/page.php';
	require SAVTA_DIR . '/inc/admin/dashboard.php';
}
