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
			<h2 class="h2 h2--sm efrat__title" id="efrat-title" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'המיזם בהובלת אפרת ברזל', 'savta' ); ?></h2>
			<p class="body-18 mb-18" <?php echo savta_reveal( 'up', 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'המיזם "סבתא על הספסל" פועל בהובלתה של הגב\' אפרת ברזל — סופרת, מטפלת רגשית ואשת תקשורת ידועה.', 'savta' ); ?></p>
			<p class="body-18 mb-24" <?php echo savta_reveal( 'up', 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'אפרת מובילה את המיזם מתוך רצון לתת מענה קהילתי רך, אחראי ואנושי לצורך הגדול שנוצר בשטח: צורך באוזן קשבת, בחיבור אנושי, במקום שבו אפשר לדבר בפשטות, בכבוד ובלב פתוח.', 'savta' ); ?></p>
			<p class="efrat__quote" <?php echo savta_reveal( 'right', 0.3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'המטרה היא לבנות מרחב קהילתי שמחזיר למרכז את הדבר הכי בסיסי והכי חסר לפעמים: מישהי טובה שמקשיבה באמת.', 'savta' ); ?></p>
		</div>
	</div>
</section>
