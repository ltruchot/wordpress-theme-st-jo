<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package st-jo
 */

get_header();
?>

	<section id="primary">
		<main id="main">

			<div>
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Page introuvable', 'st-jo' ); ?></h1>
				</header><!-- .page-header -->

				<div <?php st_jo_content_class( 'page-content' ); ?>>
					<p><?php esc_html_e( 'Cette page est introuvable. Elle a pu être supprimée ou renommée, ou n’a jamais existé.', 'st-jo' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
