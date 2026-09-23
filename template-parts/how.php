<?php
/**
 * 02 – How it works (steps with scroll-progress track).
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section how" id="how-it-works" aria-labelledby="how-title">
	<div class="container container--narrow">
		<?php savta_the_section_number( '02', 'section-num--deep' ); ?>
		<h2 class="h2 how__title" id="how-title" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'איך זה עובד?', 'savta' ); ?></h2>
		<ol class="steps" data-progress-track>
			<span class="steps__track" aria-hidden="true"></span>
			<span class="steps__fill" data-progress-fill aria-hidden="true"></span>
			<?php foreach ( savta_steps() as $savta_i => $savta_step ) : ?>
				<li class="step" <?php echo savta_reveal( 'up', $savta_i * 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-progress-step>
					<span class="step__dot" data-progress-dot aria-hidden="true"></span>
					<p class="step__num" aria-hidden="true"><?php echo esc_html( (string) ( $savta_i + 1 ) ); ?></p>
					<div class="step__body">
						<h3 class="step__title"><?php echo esc_html( $savta_step['title'] ); ?></h3>
						<p class="step__text"><?php echo esc_html( $savta_step['text'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
		<div class="how__end" aria-hidden="true"></div>
	</div>
</section>
