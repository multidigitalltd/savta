<?php
/**
 * 404 template.
 *
 * @package Savta
 */

get_header();
?>
<main id="main" class="page-main" tabindex="-1">
	<div class="container page-content">
		<h1 class="page-title"><?php esc_html_e( 'העמוד לא נמצא', 'savta' ); ?></h1>
		<p><?php esc_html_e( 'הכתובת שהגעת אליה אינה קיימת. אפשר לחזור לעמוד הראשי ולהמשיך משם.', 'savta' ); ?></p>
		<p><a class="btn btn--sage" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'חזרה לעמוד הראשי', 'savta' ); ?></a></p>
	</div>
</main>
<?php
get_footer();
