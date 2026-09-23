<?php
/**
 * 07 – FAQ accordion (one open at a time, fully keyboard/screen-reader accessible).
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section band band--sand" aria-labelledby="faq-title">
	<div class="container faq" id="faq">
		<div class="faq__intro" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php savta_the_section_number( '07', 'section-num--deep' ); ?>
			<h2 class="h2 faq__title" id="faq-title"><?php savta_e_br( 'faq_title' ); ?></h2>
			<p class="faq__lede"><?php savta_e( 'faq_lede' ); ?></p>
		</div>
		<div class="faq__list">
			<?php foreach ( savta_faqs() as $savta_i => $savta_faq ) : ?>
				<?php $savta_id = 'faq-' . ( $savta_i + 1 ); ?>
				<div class="faq__item" data-faq <?php echo savta_reveal( 'up', $savta_i * 0.06 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<h3 class="faq__q">
						<button class="faq__btn" type="button" id="<?php echo esc_attr( $savta_id . '-q' ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $savta_id . '-a' ); ?>" data-faq-q>
							<span class="faq__mark" data-faq-mark aria-hidden="true">+</span>
							<span class="faq__q-text"><?php echo esc_html( $savta_faq['q'] ?? '' ); ?></span>
						</button>
					</h3>
					<div class="faq__a" id="<?php echo esc_attr( $savta_id . '-a' ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $savta_id . '-q' ); ?>" data-faq-a hidden>
						<p><?php echo esc_html( $savta_faq['a'] ?? '' ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
