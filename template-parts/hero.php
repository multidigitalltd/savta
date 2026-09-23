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
$savta_area_note   = (string) get_theme_mod( 'savta_area_note', '' );
?>
<section class="hero" aria-labelledby="hero-title">
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
	<div class="hero__grid">
		<div class="hero__text" data-reveal>
			<p class="hero__eyebrow">
				<span class="hero__kicker"><?php esc_html_e( 'מיזם חברתי למען הקהילה', 'savta' ); ?></span>
				<span class="hero__lead-by"><?php esc_html_e( 'בהובלת אפרת ברזל', 'savta' ); ?></span>
			</p>
			<h1 class="hero__title" id="hero-title"><?php esc_html_e( 'סבתא', 'savta' ); ?><br><?php esc_html_e( 'על הספסל', 'savta' ); ?></h1>
			<div class="hero__underline" data-draw aria-hidden="true">
				<svg viewBox="0 0 300 12" fill="none" stroke="#C98573" stroke-width="3" stroke-linecap="round" focusable="false"><path d="M3 8 C70 3 150 3 297 6"/></svg>
			</div>
			<p class="hero__lede"><?php esc_html_e( 'לפעמים כל מה שצריך הוא מישהי טובה שתשב איתך רגע — ותקשיב באמת.', 'savta' ); ?></p>
			<div class="hero__actions">
				<a class="btn btn--sage" href="#signup"><?php esc_html_e( 'אני רוצה לדבר עם הסבתא', 'savta' ); ?></a>
				<a class="text-link" href="#how-it-works"><?php esc_html_e( 'איך זה עובד?', 'savta' ); ?></a>
			</div>
		</div>
		<div class="hero__logo">
			<div class="hero__halo" aria-hidden="true"></div>
			<div class="fall" aria-hidden="true">
				<?php
				foreach ( $savta_logo_petals as $savta_petal ) {
					savta_the_petal( $savta_petal );
				}
				?>
			</div>
			<?php
			savta_the_image(
				'logo',
				array(
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
		<p class="hero__info-text"><?php esc_html_e( 'שיחה אישית, חינמית ודיסקרטית עם אישה עתירת ניסיון חיים, מכילה ומקשיבה — בעלת לב רחב ונוכחות מרגיעה. מקום לפרוק, לנשום, לעשות קצת סדר, ולצאת עם כוח נוסף לצעד הבא.', 'savta' ); ?></p>
		<dl class="facts">
			<div class="facts__item">
				<dt><?php esc_html_e( 'מסגרת', 'savta' ); ?></dt>
				<dd><?php esc_html_e( 'פרויקט למען', 'savta' ); ?><br><?php esc_html_e( 'הקהילה', 'savta' ); ?></dd>
			</div>
			<div class="facts__item facts__item--mid">
				<dt><?php esc_html_e( 'עלות', 'savta' ); ?></dt>
				<dd><?php esc_html_e( 'ללא עלות', 'savta' ); ?></dd>
			</div>
			<div class="facts__item">
				<dt><?php esc_html_e( 'זימון', 'savta' ); ?></dt>
				<dd><?php esc_html_e( 'בתיאום מראש', 'savta' ); ?><br><?php esc_html_e( 'בלבד', 'savta' ); ?></dd>
			</div>
		</dl>
		<div class="area">
			<p class="area__label"><?php esc_html_e( 'אזור פעילות', 'savta' ); ?></p>
			<p class="area__text">
				<?php if ( $savta_area_note ) : ?>
					<?php echo esc_html( $savta_area_note ); ?>
				<?php else : ?>
					<strong><?php esc_html_e( 'בני ברק.', 'savta' ); ?></strong> <?php esc_html_e( 'בעזרת ה׳, הפעילות תורחב בהמשך לערים נוספות.', 'savta' ); ?>
				<?php endif; ?>
			</p>
		</div>
	</div>
</section>
