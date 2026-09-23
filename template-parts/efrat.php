<?php
/**
 * 04 – Led by Efrat Barzel.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section" aria-labelledby="efrat-title">
	<div class="container efrat">
		<div class="efrat__photo">
			<?php savta_the_window( 'efrat', 'arch-sm', array( 'sizes' => '(min-width: 760px) 434px, 100vw' ) ); ?>
		</div>
		<div class="efrat__text">
			<?php savta_the_section_number( '04', 'section-num--deep' ); ?>
			<h2 class="h2 h2--sm efrat__title" id="efrat-title" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'efrat_title' ); ?></h2>
			<p class="body-18 mb-18" <?php echo savta_reveal( 'up', 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'efrat_p1' ); ?></p>
			<p class="body-18 mb-24" <?php echo savta_reveal( 'up', 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'efrat_p2' ); ?></p>
			<p class="efrat__quote" <?php echo savta_reveal( 'right', 0.3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'efrat_quote' ); ?></p>
		</div>
	</div>
</section>
