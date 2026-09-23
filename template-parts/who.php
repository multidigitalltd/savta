<?php
/**
 * 03 – Who is it for.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section band band--sage" aria-labelledby="who-title">
	<div class="container who" id="who" data-reveal>
		<div class="who__first">
			<?php savta_the_section_number( '03', 'section-num--sage' ); ?>
			<h2 class="h2 who__title" id="who-title"><?php esc_html_e( 'למי השיחה', 'savta' ); ?><br><?php esc_html_e( 'יכולה להתאים?', 'savta' ); ?></h2>
			<p class="who__lede"><?php esc_html_e( 'למי שמרגישה צורך לעצור רגע, לשתף, לפרוק או לעשות סדר — בלי לחץ, בלי ביקורת ובלי שיפוטיות.', 'savta' ); ?></p>
			<p class="pill"><?php esc_html_e( 'מגיל 20 ומעלה', 'savta' ); ?></p>
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
		<div class="who__second">
			<ul class="checklist">
				<?php foreach ( savta_audience() as $savta_item ) : ?>
					<li class="checklist__item">
						<svg class="checklist__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4E5A3B" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4 12L10 18L20 6"/></svg>
						<span><?php echo esc_html( $savta_item ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="who__note"><?php esc_html_e( 'המיזם פועל כעת בבני ברק, ובעזרת ה׳ יורחב בהמשך לערים נוספות. מספר המקומות מוגבל והשיחות יתקיימו בהדרגה ובאחריות.', 'savta' ); ?></p>
		</div>
	</div>
</section>
