<?php
/**
 * 01 – What is "Savta al HaSafsal".
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section band band--sand" aria-labelledby="about-title">
	<div class="container about" id="about">
		<div class="about__first">
			<?php savta_the_section_number( '01', 'section-num--deep' ); ?>
			<h2 class="h2 about__title" id="about-title" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'מה זה', 'savta' ); ?><br><?php esc_html_e( '"סבתא על הספסל"?', 'savta' ); ?></h2>
			<?php savta_the_window( 'about', 'arch-down', array( 'sizes' => '(min-width: 760px) 420px, 100vw' ) ); ?>
		</div>
		<div class="about__second">
			<p class="body-18" <?php echo savta_reveal( 'up', 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( '"סבתא על הספסל" הוא מיזם חברתי שנולד מתוך צורך אמיתי בקהילה: לאפשר לאנשים לעצור לרגע, לשבת לשיחה אישית, לפרוק מה שיושב על הלב, ולקבל הקשבה טובה, מכבדת ולא שיפוטית.', 'savta' ); ?></p>
			<blockquote class="pull" <?php echo savta_reveal( 'right', 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<p><?php esc_html_e( 'זו לא קליניקה. זה לא טיפול. וזו לא שיחה שמבטיחה לפתור הכול.', 'savta' ); ?></p>
			</blockquote>
			<p class="body-18 about__last" <?php echo savta_reveal( 'up', 0.3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'זו שיחה אנושית, חמה ודיסקרטית — עם "סבתא" מקשיבה, מנוסה ורגישה, שעברה הכוונה מתאימה ומגיעה עם ניסיון חיים, לב פתוח ויכולת לראות את האדם שמולה.', 'savta' ); ?></p>
			<div class="about__goal" <?php echo savta_reveal( 'up', 0.4 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<div class="about__cup" data-draw aria-hidden="true">
					<svg viewBox="0 0 120 120" fill="none" stroke="#5F6B49" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" focusable="false">
						<path d="M30 50 H86 C86 78 74 92 58 92 C42 92 30 78 30 50 Z"/>
						<path d="M86 58 C102 58 102 80 86 80"/>
						<path d="M18 102 H98" stroke="#C98573"/>
						<path class="steam steam--1" d="M44 40 C38 31 50 27 44 17" stroke="#C98573" stroke-width="3.4"/>
						<path class="steam steam--2" d="M58 40 C52 30 64 25 58 13" stroke="#C98573" stroke-width="3.4"/>
						<path class="steam steam--3" d="M72 40 C66 31 78 27 72 17" stroke="#C98573" stroke-width="3.4"/>
					</svg>
				</div>
				<p class="about__goal-text"><?php esc_html_e( 'המטרה פשוטה: לתת מקום לדבר, לעשות סדר, ולהרגיש לרגע שלא סוחבים לבד.', 'savta' ); ?></p>
			</div>
		</div>
	</div>
</section>
