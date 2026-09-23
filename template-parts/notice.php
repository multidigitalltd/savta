<?php
/**
 * "Important to know" disclaimer block.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section section--notice" aria-labelledby="notice-title">
	<div class="container container--narrow notice" data-reveal>
		<svg class="notice__leaf" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#4E5A3B" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 3C12 3 5 8 5 13.5C5 17.6 8.1 21 12 21C15.9 21 19 17.6 19 13.5C19 8 12 3 12 3Z"/><path d="M12 21C12 16 12 12 12 9"/></svg>
		<div class="notice__body">
			<h2 class="notice__title" id="notice-title"><?php esc_html_e( 'חשוב לדעת', 'savta' ); ?></h2>
			<p class="notice__text"><?php esc_html_e( 'השיחה במסגרת "סבתא על הספסל" היא שיחת הקשבה קהילתית. היא אינה טיפול רגשי, אינה אבחון, ואינה מחליפה ייעוץ מקצועי או מענה חירום.', 'savta' ); ?></p>
			<p class="notice__text"><?php esc_html_e( 'המטרה היא לתת מקום בטוח לשיחה, הקשבה, סדר וחיזוק ראשוני.', 'savta' ); ?></p>
			<p class="notice__text notice__text--last"><?php esc_html_e( 'במקרים שבהם נראה שיש צורך בעזרה מקצועית נוספת, נעודד פנייה לגורם מתאים.', 'savta' ); ?></p>
		</div>
	</div>
</section>
