<?php
/**
 * 06 – Quote cards.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section section--quotes" aria-labelledby="quotes-title">
	<div class="container">
		<div class="quotes__head">
			<div <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php savta_the_section_number( '06', 'section-num--deep' ); ?>
				<h2 class="h2" id="quotes-title"><?php savta_e_br( 'quotes_title' ); ?></h2>
			</div>
			<p class="quotes__lede" <?php echo savta_reveal( 'up', 0.15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'quotes_lede' ); ?></p>
		</div>
		<ul class="quotes">
			<?php foreach ( savta_quotes() as $savta_i => $savta_quote ) : ?>
				<li class="quote-card quote-card--<?php echo esc_attr( (string) ( $savta_i % 3 ) ); ?>" <?php echo savta_reveal( 'scale', ( $savta_i % 4 ) * 0.08 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<p class="quote-card__text"><?php echo esc_html( $savta_quote ); ?></p>
					<svg class="quote-card__heart" width="15" height="14" viewBox="0 0 24 22" fill="#C98573" aria-hidden="true" focusable="false"><path d="<?php echo esc_attr( savta_heart_path() ); ?>"/></svg>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
