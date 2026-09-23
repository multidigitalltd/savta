<?php
/**
 * 05 – Our first "savta". Image comes from the Customizer; a styled placeholder
 * shows until the client supplies one.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

$savta_first_image = (int) get_theme_mod( 'savta_first_savta_image', 0 );
?>
<section class="section band band--blush" aria-labelledby="first-title">
	<div class="container first" data-reveal>
		<div class="first__text">
			<?php savta_the_section_number( '05', 'section-num--deep' ); ?>
			<h2 class="h2 h2--sm first__title" id="first-title"><?php esc_html_e( '"הסבתא" הראשונה שלנו', 'savta' ); ?></h2>
			<p class="body-18 mb-18"><?php esc_html_e( '"הסבתא" הראשונה במיזם היא אשת חינוך ותיקה, עם למעלה מ־40 שנות ניסיון בחינוך, מתוכן 13 שנים כמנהלת בבית ספר ידוע.', 'savta' ); ?></p>
			<p class="body-18 mb-18"><?php esc_html_e( 'לאורך השנים היא ליוותה תלמידות, הורים וצוותים חינוכיים, מתוך חכמה, רגישות, אחריות והבנה עמוקה לאנשים.', 'savta' ); ?></p>
			<p class="body-18 mb-28"><?php esc_html_e( 'היום היא מביאה אל המיזם את ניסיון החיים, הלב הרחב והיכולת המיוחדת שלה להקשיב — כדי להיות שם עבור מי שצריכה שיחה טובה, רגועה ומחזקת.', 'savta' ); ?></p>
			<p class="first__quote"><?php esc_html_e( 'לא כמטפלת. לא כשופטת. אלא כסבתא מקשיבה — עם לב פתוח ונוכחות טובה.', 'savta' ); ?></p>
		</div>
		<div class="first__photo">
			<div class="window window--side first__window" data-window>
				<?php if ( $savta_first_image && wp_attachment_is_image( $savta_first_image ) ) : ?>
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
					<div class="placeholder" role="img" aria-label="<?php esc_attr_e( 'מקום שמור לתמונה: סבתא יושבת על ספסל', 'savta' ); ?>">
						<svg class="placeholder__art" viewBox="0 0 160 120" fill="none" stroke="#C98573" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
							<path d="M28 78 H132"/><path d="M36 78 V98"/><path d="M124 78 V98"/><path d="M34 62 H126"/><path d="M34 62 V78"/><path d="M126 62 V78"/>
							<path d="M80 52 C80 52 66 43 66 35 C66 30 70 27 74 27 C77 27 79 29 80 31 C81 29 83 27 86 27 C90 27 94 30 94 35 C94 43 80 52 80 52 Z" stroke="#8F4531"/>
						</svg>
						<span class="placeholder__caption"><?php esc_html_e( 'איור: סבתא יושבת על ספסל', 'savta' ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
