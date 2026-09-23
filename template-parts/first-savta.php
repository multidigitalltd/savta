<?php
/**
 * 05 – Our first "savta". Image comes from the Customizer; a styled placeholder
 * shows until the client supplies one.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

$savta_first_image = savta_image_id( 'img_first' );
?>
<section class="section band band--blush" aria-labelledby="first-title">
	<div class="container first">
		<div class="first__text">
			<?php savta_the_section_number( '05', 'section-num--deep' ); ?>
			<h2 class="h2 h2--sm first__title" id="first-title" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'first_title' ); ?></h2>
			<p class="body-18 mb-18" <?php echo savta_reveal( 'up', 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'first_p1' ); ?></p>
			<p class="body-18 mb-18" <?php echo savta_reveal( 'up', 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'first_p2' ); ?></p>
			<p class="body-18 mb-28" <?php echo savta_reveal( 'up', 0.3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'first_p3' ); ?></p>
			<p class="first__quote" <?php echo savta_reveal( 'up', 0.4 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'first_quote' ); ?></p>
		</div>
		<div class="first__photo">
			<div class="window-wrap" <?php echo savta_reveal( 'scale', 0.15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="window window--side first__window" data-window>
				<?php if ( $savta_first_image ) : ?>
					<?php
					echo wp_get_attachment_image(
						$savta_first_image,
						'large',
						false,
						array(
							'class'    => 'window__img',
							'loading'  => 'lazy',
							'decoding' => 'async',
							'data-fit' => '1',
							'sizes'    => '(min-width: 760px) 434px, 100vw',
						)
					);
					?>
				<?php else : ?>
					<div class="placeholder" role="img" aria-label="<?php echo esc_attr( savta_text( 'first_caption' ) ); ?>">
						<svg class="placeholder__art" viewBox="0 0 160 120" fill="none" stroke="#C98573" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
							<path d="M28 78 H132"/><path d="M36 78 V98"/><path d="M124 78 V98"/><path d="M34 62 H126"/><path d="M34 62 V78"/><path d="M126 62 V78"/>
							<path d="M80 52 C80 52 66 43 66 35 C66 30 70 27 74 27 C77 27 79 29 80 31 C81 29 83 27 86 27 C90 27 94 30 94 35 C94 43 80 52 80 52 Z" stroke="#8F4531"/>
						</svg>
						<span class="placeholder__caption"><?php savta_e( 'first_caption' ); ?></span>
					</div>
				<?php endif; ?>
			</div>
			</div>
		</div>
	</div>
</section>
