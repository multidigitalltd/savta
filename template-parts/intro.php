<?php
/**
 * Intro: arched bench-garden frame beside large text.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section intro" aria-label="<?php esc_attr_e( 'פתיח', 'savta' ); ?>">
	<div class="container intro__grid">
		<?php savta_the_window( 'intro', 'arch', array( 'sizes' => '(min-width: 760px) 50vw, 100vw' ) ); ?>
		<div class="intro__text">
			<div class="intro__heart" data-draw aria-hidden="true">
				<svg viewBox="0 0 120 110" fill="none" stroke="#C98573" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M60 98 C60 98 16 68 16 42 C16 25 28 15 41 15 C50 15 56 20 60 27 C64 20 70 15 79 15 C92 15 104 25 104 42 C104 68 60 98 60 98 Z"/></svg>
			</div>
			<p class="intro__big" <?php echo savta_reveal( 'up', 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'יש רגעים שבהם הכול נראה "בסדר" מבחוץ, אבל בפנים יש עומס, מחשבות, דאגה או תחושה שאין באמת עם מי לדבר.', 'savta' ); ?></p>
			<p class="intro__body" <?php echo savta_reveal( 'up', 0.25 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'לא תמיד צריך פתרונות גדולים. לפעמים צריך מקום שקט, לב פתוח, ואוזן קשבת של מישהי שכבר ראתה הרבה בחיים — ויודעת להקשיב בלי לשפוט.', 'savta' ); ?></p>
		</div>
	</div>
</section>
