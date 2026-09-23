<?php
/**
 * Document head and sticky navigation.
 *
 * @package Savta
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'דילוג לתוכן הראשי', 'savta' ); ?></a>
<header class="site-nav" id="top">
	<div class="site-nav__row">
		<?php savta_the_nav_logo(); ?>
		<nav class="site-nav__links" aria-label="<?php esc_attr_e( 'ניווט ראשי', 'savta' ); ?>">
			<?php
			$savta_home = is_front_page() ? '' : home_url( '/' );
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '<ul class="site-nav__list">%3$s</ul>',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} else {
				echo '<ul class="site-nav__list">';
				foreach ( savta_nav_links() as $savta_link ) {
					printf(
						'<li><a href="%1$s">%2$s</a></li>',
						esc_url( $savta_home . $savta_link['href'] ),
						esc_html( $savta_link['label'] )
					);
				}
				echo '</ul>';
			}
			?>
			<a class="btn btn--rose btn--nav" href="<?php echo esc_url( $savta_home . '#signup' ); ?>"><?php esc_html_e( 'לדבר עם הסבתא', 'savta' ); ?></a>
		</nav>
	</div>
</header>
