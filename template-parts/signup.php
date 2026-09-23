<?php
/**
 * 08 – Booking form. Posts to admin-post.php; behaviors.js upgrades it to an
 * in-place submit. `?savta=sent|error` renders the no-JS result states.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

$savta_state = savta_form_state();
?>
<section class="section signup" id="signup" aria-labelledby="signup-title">
	<div class="container signup__grid">
		<div class="signup__intro">
			<?php savta_the_section_number( '08', 'section-num--deep' ); ?>
			<h2 class="h2 signup__title" id="signup-title" <?php echo savta_reveal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e_br( 'signup_title' ); ?></h2>
			<p class="signup__lede" <?php echo savta_reveal( 'up', 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'signup_lede' ); ?></p>
			<?php if ( '' !== savta_text( 'signup_limited' ) ) : ?>
			<p class="signup__limited" <?php echo savta_reveal( 'up', 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php savta_e( 'signup_limited' ); ?></p>
			<?php endif; ?>
			<?php savta_the_window( 'signup', 'leaf', array( 'sizes' => '(min-width: 760px) 420px, 100vw' ) ); ?>
		</div>
		<div class="signup__form-col" <?php echo savta_reveal( 'scale', 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( 'sent' === $savta_state ) : ?>
				<?php get_template_part( 'template-parts/thanks' ); ?>
			<?php else : ?>
				<form class="form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-form>
					<input type="hidden" name="action" value="<?php echo esc_attr( SAVTA_FORM_ACTION ); ?>">
					<input type="hidden" name="<?php echo esc_attr( SAVTA_NONCE_FIELD ); ?>" value="<?php echo esc_attr( savta_create_nonce() ); ?>" data-nonce>
					<p class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>

					<div class="form__status" role="status" aria-live="polite" data-form-status>
						<?php if ( 'error' === $savta_state ) : ?>
							<p class="form__error-banner" role="alert"><?php esc_html_e( 'הטופס לא נשלח. נא לבדוק את הפרטים ולנסות שוב, או להתקשר אלינו.', 'savta' ); ?></p>
						<?php endif; ?>
					</div>

					<div class="form__grid">
						<div class="form__row">
							<div class="field">
								<label class="field__label" for="f-name"><?php esc_html_e( 'שם פרטי', 'savta' ); ?> <span class="req" aria-hidden="true">*</span></label>
								<input class="field__input" id="f-name" name="name" type="text" required autocomplete="given-name" minlength="2" maxlength="60" aria-describedby="f-name-err" aria-required="true">
								<p class="field__error" id="f-name-err" data-error-for="name"></p>
							</div>
							<div class="field">
								<label class="field__label" for="f-phone"><?php esc_html_e( 'טלפון', 'savta' ); ?> <span class="req" aria-hidden="true">*</span></label>
								<input class="field__input" id="f-phone" name="phone" type="tel" required autocomplete="tel" inputmode="tel" maxlength="20" aria-describedby="f-phone-err" aria-required="true">
								<p class="field__error" id="f-phone-err" data-error-for="phone"></p>
							</div>
							<div class="field">
								<label class="field__label" for="f-city"><?php esc_html_e( 'עיר', 'savta' ); ?></label>
								<input class="field__input" id="f-city" name="city" type="text" autocomplete="address-level2" maxlength="60" aria-describedby="f-city-err">
								<p class="field__error" id="f-city-err" data-error-for="city"></p>
							</div>
							<div class="field">
								<label class="field__label" for="f-age"><?php esc_html_e( 'גיל', 'savta' ); ?></label>
								<input class="field__input" id="f-age" name="age" type="number" min="16" max="120" inputmode="numeric" aria-describedby="f-age-err">
								<p class="field__error" id="f-age-err" data-error-for="age"></p>
							</div>
					</div>

					<fieldset class="fieldset">
						<legend class="field__label fieldset__legend"><?php esc_html_e( 'איך נוח ליצור קשר?', 'savta' ); ?></legend>
						<div class="radios">
							<label class="radio"><input type="radio" name="contact" value="phone" checked><?php esc_html_e( 'טלפון', 'savta' ); ?></label>
							<label class="radio"><input type="radio" name="contact" value="whatsapp"><?php esc_html_e( 'וואטסאפ', 'savta' ); ?></label>
						</div>
					</fieldset>

					<?php if ( savta_setting( 'calendar_enabled' ) ) : ?>
					<div class="cal" data-cal>
						<p class="field__label cal__label" id="cal-label"><?php esc_html_e( 'מתי נוח לך?', 'savta' ); ?></p>
						<button class="cal__toggle" type="button" aria-expanded="false" aria-controls="cal-panel" aria-describedby="cal-label" data-cal-toggle>
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C98573" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M3 10H21"/><path d="M8 3V6"/><path d="M16 3V6"/></svg>
							<span class="cal__value" data-cal-value><?php esc_html_e( 'לבדיקת ימים פנויים', 'savta' ); ?></span>
							<span class="cal__hint" data-cal-toggle-text><?php esc_html_e( 'לצפייה ביומן', 'savta' ); ?></span>
						</button>
						<div class="cal__panel" id="cal-panel" data-cal-panel hidden>
							<p class="cal__note"><?php savta_e( 'cal_note' ); ?></p>
							<div class="cal__days" data-cal-days></div>
							<p class="cal__note cal__note--small"><?php savta_e( 'cal_note_small' ); ?></p>
						</div>
						<p class="field__error" id="f-slot-err" data-error-for="slot"></p>
						<p class="sr-only" aria-live="polite" data-cal-announce></p>
					</div>
					<?php endif; ?>

					<div class="field">
						<label class="field__label" for="f-topic"><?php esc_html_e( 'בכמה מילים: על מה היית רוצה לשוחח?', 'savta' ); ?></label>
						<textarea class="field__input field__textarea" id="f-topic" name="topic" rows="3" maxlength="1500" aria-describedby="f-topic-err"></textarea>
						<p class="field__error" id="f-topic-err" data-error-for="topic"></p>
					</div>

					<div class="field">
						<label class="consent">
							<input type="checkbox" name="consent" value="1" required aria-describedby="f-consent-err" aria-required="true">
							<span><?php savta_e( 'form_consent' ); ?></span>
						</label>
						<p class="field__error" id="f-consent-err" data-error-for="consent"></p>
					</div>

					<button class="btn btn--rose btn--submit" type="submit" data-submit><?php savta_e( 'form_submit' ); ?></button>
					</div>
				</form>
			<?php endif; ?>
		</div>
	</div>
</section>
