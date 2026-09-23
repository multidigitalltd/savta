<?php
/**
 * Hero (classic variant) + info strip.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

// Falling layers: values copied verbatim from the design source.
$savta_hero_petals = array(
	array(
		'type'    => 'petal',
		'right'   => '6%',
		'anim'    => '23s linear 1.5s infinite',
		'w'       => 13,
		'h'       => 10,
		'fill'    => '#C9A783',
		'opacity' => '0.4',
	),
	array(
		'type'    => 'heart',
		'right'   => '52%',
		'anim'    => '27s linear 6s infinite',
		'w'       => 12,
		'h'       => 11,
		'fill'    => '#D8A08D',
		'opacity' => '0.35',
	),
	array(
		'type'    => 'petal',
		'right'   => '92%',
		'anim'    => '25s linear 12s infinite',
		'w'       => 14,
		'h'       => 10,
		'fill'    => '#B3BC9B',
		'opacity' => '0.4',
	),
	array(
		'type'    => 'heart',
		'right'   => '32%',
		'anim'    => '16s linear 2s infinite',
		'w'       => 14,
		'h'       => 13,
		'fill'    => '#C98573',
		'opacity' => '0.45',
	),
	array(
		'type'    => 'heart',
		'right'   => '76%',
		'anim'    => '19s linear 9s infinite',
		'w'       => 12,
		'h'       => 11,
		'fill'    => '#D8A08D',
		'opacity' => '0.5',
	),
	array(
		'type'    => 'heart',
		'right'   => '44%',
		'anim'    => '21s linear 15s infinite',
		'w'       => 16,
		'h'       => 15,
		'fill'    => '#C98573',
		'opacity' => '0.38',
	),
);
$savta_hero_birds  = array(
	array(
		'top'          => '16%',
		'right'        => '-6%',
		'anim'         => '26s linear infinite',
		'w'            => 26,
		'h'            => 12,
		'stroke_width' => '1.6',
	),
	array(
		'top'          => '24%',
		'right'        => '-10%',
		'anim'         => '31s linear 4s infinite',
		'w'            => 20,
		'h'            => 10,
		'stroke_width' => '1.8',
	),
	array(
		'top'          => '9%',
		'right'        => '-8%',
		'anim'         => '36s linear 11s infinite',
		'w'            => 15,
		'h'            => 8,
		'stroke_width' => '2',
	),
);
$savta_logo_petals = array(
	array(
		'type'    => 'heart',
		'right'   => '14%',
		'anim'    => '13s linear infinite',
		'w'       => 15,
		'h'       => 14,
		'fill'    => '#C98573',
		'opacity' => '0.5',
	),
	array(
		'type'    => 'petal',
		'right'   => '38%',
		'anim'    => '17s linear 2.5s infinite',
		'w'       => 17,
		'h'       => 12,
		'fill'    => '#9CA884',
		'opacity' => '0.55',
	),
	array(
		'type'    => 'heart',
		'right'   => '62%',
		'anim'    => '15s linear 5s infinite',
		'w'       => 13,
		'h'       => 12,
		'fill'    => '#D8A08D',
		'opacity' => '0.45',
	),
	array(
		'type'    => 'petal',
		'right'   => '84%',
		'anim'    => '19s linear 7.5s infinite',
		'w'       => 15,
		'h'       => 11,
		'fill'    => '#B3BC9B',
		'opacity' => '0.5',
	),
	array(
		'type'    => 'petal',
		'right'   => '26%',
		'anim'    => '21s linear 10s infinite',
		'w'       => 12,
		'h'       => 9,
		'fill'    => '#C9A783',
		'opacity' => '0.45',
	),
	array(
		'type'    => 'heart',
		'right'   => '6%',
		'anim'    => '14s linear 1.5s infinite',
		'w'       => 16,
		'h'       => 15,
		'fill'    => '#C98573',
		'opacity' => '0.55',
	),
	array(
		'type'    => 'heart',
		'right'   => '50%',
		'anim'    => '12s linear 3.5s infinite',
		'w'       => 13,
		'h'       => 12,
		'fill'    => '#D8A08D',
		'opacity' => '0.5',
	),
	array(
		'type'    => 'heart',
		'right'   => '72%',
		'anim'    => '16s linear 6.5s infinite',
		'w'       => 11,
		'h'       => 10,
		'fill'    => '#C98573',
		'opacity' => '0.45',
	),
	array(
		'type'    => 'heart',
		'right'   => '92%',
		'anim'    => '18s linear 8.5s infinite',
		'w'       => 14,
		'h'       => 13,
		'fill'    => '#D8A08D',
		'opacity' => '0.4',
	),
	array(
		'type'    => 'heart',
		'right'   => '32%',
		'anim'    => '20s linear 11.5s infinite',
		'w'       => 12,
		'h'       => 11,
		'fill'    => '#C98573',
		'opacity' => '0.42',
	),
);
?>
<?php $savta_petals_on = (bool) savta_setting( 'petals' ); ?>
<section class="hero" aria-labelledby="hero-title">
	<?php if ( $savta_petals_on ) : ?>
	<div class="fall" aria-hidden="true">
		<?php
		foreach ( $savta_hero_petals as $savta_petal ) {
			savta_the_petal( $savta_petal );
		}
		foreach ( $savta_hero_birds as $savta_bird ) {
			savta_the_bird( $savta_bird );
		}
		?>
	</div>
	<?php endif; ?>
	<div class="hero__grid">
		<div class="hero__text">
			<p class="hero__eyebrow" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<span class="hero__kicker"><?php savta_e( 'hero_kicker' ); ?></span>
				<span class="hero__lead-by"><?php savta_e( 'hero_lead_by' ); ?></span>
			</p>
			<h1 class="hero__title" id="hero-title" <?php echo savta_reveal( 'up', 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e_br( 'hero_title' ); ?></h1>
			<div class="hero__underline" data-draw aria-hidden="true">
				<svg viewBox="0 0 300 12" fill="none" stroke="#C98573" stroke-width="3" stroke-linecap="round" focusable="false"><path d="M3 8 C70 3 150 3 297 6"/></svg>
			</div>
			<p class="hero__lede" <?php echo savta_reveal( 'up', 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'hero_lede' ); ?></p>
			<div class="hero__actions" <?php echo savta_reveal( 'up', 0.3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<a class="btn btn--sage" href="#signup"><?php savta_e( 'hero_btn' ); ?></a>
				<a class="text-link" href="#how-it-works"><?php savta_e( 'hero_link' ); ?></a>
			</div>
		</div>
		<div class="hero__logo">
			<div class="hero__halo" aria-hidden="true"></div>
			<?php if ( $savta_petals_on ) : ?>
			<div class="fall" aria-hidden="true">
				<?php
				foreach ( $savta_logo_petals as $savta_petal ) {
					savta_the_petal( $savta_petal );
				}
				?>
			</div>
			<?php endif; ?>
			<?php
			savta_the_image(
				'logo',
				array(
					'data-reveal'   => 'scale',
					'style'         => '--reveal-delay:.15s',
					'class'         => 'hero__logo-img',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'srcset'        => savta_image_url( 'logo-400.webp' ) . ' 400w, ' . savta_image_url( 'logo-800.webp' ) . ' 800w',
					'sizes'         => '(min-width: 760px) min(620px, 48vw), 100vw',
				)
			);
			?>
		</div>
	</div>
	<div class="hero__info">
		<p class="hero__info-text" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'hero_info' ); ?></p>
		<?php $savta_facts = savta_list( 'hero_facts' ); ?>
		<?php if ( $savta_facts ) : ?>
		<dl class="facts" <?php echo savta_reveal( 'up', 0.15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php foreach ( $savta_facts as $savta_i => $savta_fact ) : ?>
				<div class="facts__item <?php echo 1 === $savta_i % 3 ? 'facts__item--mid' : ''; ?>">
					<dt><?php echo esc_html( $savta_fact['label'] ?? '' ); ?></dt>
					<dd><?php echo nl2br( esc_html( $savta_fact['value'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html before nl2br. ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
		<?php endif; ?>
	</div>
</section>
