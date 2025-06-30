<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default. Please note that
 * this is the WordPress construct of pages: specifically, posts with a post
 * type of `page`.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package st-jo
 */

get_header();
?>

	<section id="primary">
		<main id="main">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php st_jo_post_thumbnail(); ?>

					<div <?php st_jo_content_class( 'entry-content' ); ?>>
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div>' . __( 'Pages:', 'st-jo' ),
								'after'  => '</div>',
							)
						);
						?>
					</div><!-- .entry-content -->
					
					<!-- Test du composant Carousel Masked Image -->
					<div style="padding: 40px; text-align: center;">
						<h3>Test Carousel Masked Image Responsive</h3>
						
						<!-- Version responsive par défaut -->
						<div 
							class="carousel-masked-image"
							style="--carousel-image-url: url('<?php echo get_template_directory_uri(); ?>/assets/images/enfants-jouant-a-l-ecole.jpg'); margin: 20px auto; display: block;">
						</div>
						
						<!-- Version small pour test -->
						<div 
							class="carousel-masked-image carousel-masked-image--small"
							style="--carousel-image-url: url('<?php echo get_template_directory_uri(); ?>/assets/images/enfants-jouant-a-l-ecole.jpg'); margin: 20px auto; display: block;">
						</div>
					</div>

					<?php if ( get_edit_post_link() ) : ?>
						<footer class="entry-footer">
							<?php
							edit_post_link(
								sprintf(
									wp_kses(
										/* translators: %s: Name of current post. Only visible to screen readers. */
										__( 'Edit <span class="sr-only">%s</span>', 'st-jo' ),
										array(
											'span' => array(
												'class' => array(),
											),
										)
									),
									get_the_title()
								)
							);
							?>
						</footer><!-- .entry-footer -->
					<?php endif; ?>

				</article><!-- #post-<?php the_ID(); ?> -->

				<?php
				// If comments are open, or we have at least one comment, load
				// the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
