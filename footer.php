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
			<p class="site-footer__title"><?php savta_e( 'footer_title' ); ?></p>
			<p class="site-footer__text"><?php savta_e( 'footer_text' ); ?></p>
		</div>
		<div class="site-footer__meta">
			<p class="site-footer__text"><?php savta_e( 'footer_lead' ); ?></p>
			<p class="site-footer__small"><?php savta_e( 'footer_small' ); ?></p>
			<?php if ( $savta_a11y_url ) : ?>
				<p class="site-footer__small"><a class="site-footer__link" href="<?php echo esc_url( $savta_a11y_url ); ?>"><?php esc_html_e( 'הצהרת נגישות', 'savta' ); ?></a></p>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
