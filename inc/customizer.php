<?php
/**
 * Customizer: the few settings the client needs to change without code.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the "Savta" customizer section.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function savta_customize_register( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'savta_settings',
		array(
			'title'    => __( 'סבתא על הספסל', 'savta' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'savta_lead_email',
		array(
			'type'              => 'theme_mod',
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
			'capability'        => 'manage_options',
		)
	);
	$wp_customize->add_control(
		'savta_lead_email',
		array(
			'label'       => __( 'כתובת לקבלת פניות', 'savta' ),
			'description' => __( 'ריק = כתובת מנהל האתר. אפשר לציין כמה כתובות מופרדות בפסיק.', 'savta' ),
			'section'     => 'savta_settings',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'savta_first_savta_image',
		array(
			'type'              => 'theme_mod',
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'capability'        => 'manage_options',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'savta_first_savta_image',
			array(
				'label'       => __( 'תמונה: "הסבתא" הראשונה שלנו (05)', 'savta' ),
				'description' => __( 'מומלץ ריבוע (1:1) ב-WebP, לפחות 900×900. עד שתועלה תמונה מוצג מקום שמור מעוצב.', 'savta' ),
				'section'     => 'savta_settings',
				'mime_type'   => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'savta_customize_register' );

/**
 * Recipient list for lead notifications (falls back to the admin email).
 *
 * @return string[]
 */
function savta_lead_recipients(): array {
	$raw    = (string) get_theme_mod( 'savta_lead_email', '' );
	$emails = array_filter( array_map( 'sanitize_email', explode( ',', $raw ) ) );
	if ( empty( $emails ) ) {
		$emails = array( get_option( 'admin_email' ) );
	}
	return (array) apply_filters( 'savta_lead_recipients', array_values( $emails ) );
}
