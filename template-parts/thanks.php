<?php
/**
 * Thank-you card shown after a successful submission (no-JS path).
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="thanks" role="status" tabindex="-1" data-thanks>
	<svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#4E5A3B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10"/><path d="M8 12.5L11 15.5L16 9.5"/></svg>
	<p class="thanks__title"><?php esc_html_e( 'תודה, הפרטים התקבלו.', 'savta' ); ?></p>
	<p class="thanks__text"><?php esc_html_e( 'נחזור אלייך בהקדם לתיאום או להסבר נוסף.', 'savta' ); ?></p>
</div>
