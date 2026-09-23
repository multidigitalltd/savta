<?php
/**
 * Site footer.
 *
 * @package Savta
 */

$savta_a11y_url = savta_accessibility_page_url();
?>
<footer class="site-footer">
	<div class="site-footer__grid">
		<div>
			<p class="site-footer__title"><?php echo esc_html( get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : __( 'סבתא על הספסל', 'savta' ) ); ?></p>
			<p class="site-footer__text"><?php esc_html_e( 'מיזם חברתי של הקשבה, חום אנושי ותמיכה קהילתית.', 'savta' ); ?></p>
		</div>
		<div class="site-footer__meta">
			<p class="site-footer__text"><?php esc_html_e( 'בהובלת אפרת ברזל.', 'savta' ); ?></p>
			<p class="site-footer__small"><?php esc_html_e( 'השירות אינו מחליף טיפול מקצועי או מענה חירום.', 'savta' ); ?></p>
			<?php if ( $savta_a11y_url ) : ?>
				<p class="site-footer__small"><a class="site-footer__link" href="<?php echo esc_url( $savta_a11y_url ); ?>"><?php esc_html_e( 'הצהרת נגישות', 'savta' ); ?></a></p>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
