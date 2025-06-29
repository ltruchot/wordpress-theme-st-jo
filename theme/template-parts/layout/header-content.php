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
			<ul>
				<li><a href="/ecole" <?php if(is_page('ecole')) echo 'class="current-menu-item"'; ?>>L'école</a></li>
				<li><a href="/services-periscolaires" <?php if(is_page('services-periscolaires')) echo 'class="current-menu-item"'; ?>>Services périscolaires</a></li>
				<li><a href="/associations" <?php if(is_page('associations')) echo 'class="current-menu-item"'; ?>>Les associations</a></li>
				<li><a href="/infos-pratiques" <?php if(is_page('infos-pratiques')) echo 'class="current-menu-item"'; ?>>Infos pratiques</a></li>
				<li><a href="/actualites" <?php if(is_page('actualites')) echo 'class="current-menu-item"'; ?>>Actualités</a></li>
			</ul>
			<?php st_jo_button( array(
				'text' => 'Nous contacter',
				'url'  => '/contact'
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
		<ul>
			<li><a href="/ecole" <?php if(is_page('ecole')) echo 'class="current-menu-item"'; ?>>L'école</a></li>
			<li><a href="/services-periscolaires" <?php if(is_page('services-periscolaires')) echo 'class="current-menu-item"'; ?>>Services périscolaires</a></li>
			<li><a href="/associations" <?php if(is_page('associations')) echo 'class="current-menu-item"'; ?>>Les associations</a></li>
			<li><a href="/infos-pratiques" <?php if(is_page('infos-pratiques')) echo 'class="current-menu-item"'; ?>>Infos pratiques</a></li>
			<li><a href="/actualites" <?php if(is_page('actualites')) echo 'class="current-menu-item"'; ?>>Actualités</a></li>
		</ul>
		<?php st_jo_button( array(
			'text' => 'Nous contacter',
			'url'  => '/contact'
		) ); ?>
	</nav>
</header><!-- #masthead -->
