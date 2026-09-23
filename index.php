<?php
/**
 * Fallback template (blog index / archives / search).
 *
 * The site is a one-page landing page, so this template is intentionally minimal
 * and only used when WordPress cannot resolve a more specific template.
 *
 * @package Savta
 */

get_header();
?>
<main id="main" class="page-main" tabindex="-1">
	<div class="container page-content">
		<?php if ( have_posts() ) : ?>
			<h1 class="page-title"><?php esc_html_e( 'תוכן', 'savta' ); ?></h1>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="entry-summary"><?php the_excerpt(); ?></div>
				</article>
				<?php
			endwhile;
			the_posts_pagination(
				array(
					'prev_text' => esc_html__( 'הקודם', 'savta' ),
					'next_text' => esc_html__( 'הבא', 'savta' ),
				)
			);
		else :
			?>
			<h1 class="page-title"><?php esc_html_e( 'לא נמצא תוכן', 'savta' ); ?></h1>
			<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'חזרה לעמוד הראשי', 'savta' ); ?></a></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
