<?php
/**
 * Template part for displaying a message when posts are not found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package st-jo
 */

?>

<section>

	<header class="page-header">
		<?php if ( is_search() ) : ?>

			<?php
			printf(
				/* translators: 1: search result title. 2: search term. */
				'<h1 class="page-title">%1$s <span>%2$s</span></h1>',
				esc_html__( 'Résultats de recherche pour :', 'st-jo' ),
				esc_html( get_search_query() )
			);
			?>

		<?php else : ?>

			<h1 class="page-title"><?php esc_html_e( 'Aucun résultat', 'st-jo' ); ?></h1>

		<?php endif; ?>
	</header><!-- .page-header -->

	<div <?php st_jo_content_class( 'page-content' ); ?>>
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :
			?>

			<p>
				<?php esc_html_e( 'La page d’accueil est réglée pour afficher les articles les plus récents, mais aucun article n’a encore été publié.', 'st-jo' ); ?>
			</p>

			<p>
				<a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">
					<?php
					/* translators: 1: link to WP admin new post page. */
					esc_html_e( 'Ajouter ou publier un article', 'st-jo' );
					?>
				</a>
			</p>

			<?php
		elseif ( is_search() ) :
			?>

			<p>
				<?php esc_html_e( 'Cette recherche n’a rien donné. Essayez avec d’autres mots.', 'st-jo' ); ?>
			</p>

			<?php
			get_search_form();
		else :
			?>

			<p>
				<?php esc_html_e( 'Aucun contenu ne correspond à cette demande.', 'st-jo' ); ?>
			</p>

			<?php
			get_search_form();
		endif;
		?>
	</div><!-- .page-content -->

</section>
