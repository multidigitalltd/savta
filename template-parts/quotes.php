<?php
/**
 * 06 – Quote cards.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section section--quotes" aria-labelledby="quotes-title">
	<div class="container" data-reveal>
		<div class="quotes__head">
			<div>
				<?php savta_the_section_number( '06', 'section-num--deep' ); ?>
				<h2 class="h2" id="quotes-title"><?php esc_html_e( 'משפטים שסבתא', 'savta' ); ?><br><?php esc_html_e( 'אומרת על הספסל', 'savta' ); ?></h2>
			</div>
			<p class="quotes__lede"><?php esc_html_e( 'קחי אחד לדרך. אפשר גם להדפיס אותם ולתלות על המקרר.', 'savta' ); ?></p>
		</div>
		<ul class="quotes">
			<?php foreach ( savta_quotes() as $savta_i => $savta_quote ) : ?>
				<li class="quote-card quote-card--<?php echo esc_attr( (string) ( $savta_i % 3 ) ); ?>">
					<p class="quote-card__text"><?php echo esc_html( $savta_quote ); ?></p>
					<svg class="quote-card__heart" width="15" height="14" viewBox="0 0 24 22" fill="#C98573" aria-hidden="true" focusable="false"><path d="<?php echo esc_attr( savta_heart_path() ); ?>"/></svg>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
