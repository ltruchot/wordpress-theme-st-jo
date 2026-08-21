<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package st-jo
 */

?>

<header id="masthead" class="estjo-header">
	<div class="estjo-header__container">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="estjo-header__logo" rel="home">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Logotype.png' ); ?>" alt="Logo École Saint-Joseph">
		</a>

		<nav id="site-navigation" class="estjo-header__nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'st-jo' ); ?>">
			<?php st_jo_nav_list(); ?>
			<?php st_jo_button( array(
				'text' => 'Nous contacter',
				'url'  => '/contact/'
			) ); ?>
		</nav>

		<button class="estjo-header__mobile-toggle" aria-controls="mobile-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Montrer le menu', 'st-jo' ); ?>">
			<span></span>
			<span></span>
			<span></span>
			<div class="estjo-header__mobile-toggle-text">MENU</div>
		</button>
	</div>

	<!-- Mobile menu -->
	<nav id="mobile-menu" class="estjo-header__mobile-menu" aria-label="<?php esc_attr_e( 'Navigation mobile', 'st-jo' ); ?>">
		<?php st_jo_nav_list(); ?>
		<?php st_jo_button( array(
			'text' => 'Nous contacter',
			'url'  => '/contact/'
		) ); ?>
	</nav>
</header><!-- #masthead -->
