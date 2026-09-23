<?php
/**
 * 03 – Who is it for.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section band band--sage" aria-labelledby="who-title">
	<div class="container who" id="who">
		<div class="who__first">
			<?php savta_the_section_number( '03', 'section-num--sage' ); ?>
			<h2 class="h2 who__title" id="who-title" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e_br( 'who_title' ); ?></h2>
			<p class="who__lede" <?php echo savta_reveal( 'up', 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'who_lede' ); ?></p>
			<?php if ( '' !== savta_text( 'who_pill' ) ) : ?>
			<p class="pill" <?php echo savta_reveal( 'up', 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'who_pill' ); ?></p>
			<?php endif; ?>
			<div class="window-wrap" <?php echo savta_reveal( 'scale', 0.3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="window window--circle who__window" data-window>
				<?php
				savta_the_image(
					'who',
					array(
						'class' => 'window__img',
						'sizes' => '(min-width: 760px) 354px, 100vw',
					)
				);
				?>
				<svg class="who__steam" viewBox="0 0 120 90" fill="none" stroke="#8F9B78" stroke-width="4" stroke-linecap="round" aria-hidden="true" focusable="false">
					<path class="steam steam--1" d="M34 84 C26 68 42 60 34 44"/>
					<path class="steam steam--2b" d="M60 84 C52 66 68 56 60 36"/>
					<path class="steam steam--3b" d="M86 84 C78 68 94 60 86 44"/>
				</svg>
			</div>
			</div>
		</div>
		<div class="who__second">
			<ul class="checklist">
				<?php foreach ( savta_audience() as $savta_i => $savta_item ) : ?>
					<li class="checklist__item" <?php echo savta_reveal( 'up', $savta_i * 0.08 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<svg class="checklist__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4E5A3B" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4 12L10 18L20 6"/></svg>
						<span><?php echo esc_html( $savta_item ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="who__note" <?php echo savta_reveal( 'up', 0.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'who_note' ); ?></p>
		</div>
	</div>
</section>
