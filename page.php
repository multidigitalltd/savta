<?php
/**
 * Single page template (used e.g. for the accessibility statement page).
 *
 * @package Savta
 */

get_header();
?>
<main id="main" class="page-main" tabindex="-1">
	<div class="container page-content">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
