<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package st-jo
 */

$st_jo_school = st_jo_school();
?>
<section class="estjo-footer-for-parents max-w-[1200px] mx-0 md:mx-auto mb-8 md:mb-24">
	<!-- MEA quadrillée -->
	<div class="wp-block-group is-style-mea-quadrillee">
		<div class="flex flex-col md:flex-row gap-8 md:gap-0 relative min-h-[260px] px-8 md:px-16 py-8 md:py-0">
			<!-- Left column - 2/3 width on desktop, full on mobile -->
			<div class="w-full md:w-2/3 md:pr-12">
				<h2>Pour les parents...</h2>
				<div class="flex flex-col gap-4 md:flex-row">
					<div class="w-full md:w-1/2">
						<h3 class="estjo-footer-for-parents__subtitle">Noéfil</h3>
						<p>Utilisez le portail sécurisé Noéfil afin de faciliter le règlement de la scolarité de votre enfant ainsi que celui de la cantine.</p>
						<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex my-8">
							<div class="wp-block-button is-style-chevron-right">
								<a class="wp-block-button__link wp-element-button" href="https://www.noefil.fr/portail" target="_blank" rel="noopener">Portail Noéfil</a>
							</div>
						</div>
					</div>
					<div class="w-full md:w-1/2">
					<h3 class="estjo-footer-for-parents__subtitle">Carnet en ligne Éducartable</h3>
						<p>Suivez simplement la scolarité de votre enfant grâce à une application gratuite et sans publicité pour les familles.</p>
						<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex my-8">
							<div class="wp-block-button is-style-chevron-right">
								<a class="wp-block-button__link wp-element-button" href="https://www.educartable.com/" target="_blank" rel="noopener">Éducartable</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Separator line - vertical on desktop, horizontal on mobile -->
			<div class="mea-quadrillee-separator"></div>
			
			<!-- Right column - 1/3 width on desktop, full on mobile -->
			<div class="w-full md:w-1/3 md:pl-12">
				<h3 class="estjo-footer-for-parents__subtitle">Inscription</h3>
				<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex my-8">
					<div class="wp-block-button is-style-chevron-right">
						<a class="wp-block-button__link wp-element-button" href="https://www.noefil.fr/inscription?portail=QjEyNA==" target="_blank" rel="noopener">Via Noéfil</a>
					</div>
				</div>
				<p class="space-y-2">
				  Pour inscrire votre enfant au cours de l’année scolaire, prenez contact avec l’école. En cas d’absence, laissez-nous votre message et vos coordonnées afin que nous puissions vous rappeler.
				</p>
			</div>
		</div>
	</div>
</section>
<footer class="estjo-footer">
	<!-- Background foating colored shapes -->
	<div class="estjo-footer__shapes">
		<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/footer-shape-1.svg' ) ); ?>"
			alt="">
		<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/footer-shape-2.svg' ) ); ?>"
			alt="">
	</div>
	<!-- Background header to decorate the footer top -->
	<div class="estjo-footer__background-decoration">
	</div>
	<div class="estjo-footer__container">

		<div class="estjo-footer__content">
			<section>
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-enseignement-catholique.png' ) ); ?>"
					alt="Logo de l'enseignement catholique">
			</section>
			<section>
				<h2 class="estjo-footer__title">L'école</h2>
				<address>
					<?php echo esc_html( $st_jo_school['name'] ); ?><br>
					<?php echo esc_html( $st_jo_school['street'] ); ?><br>
					<?php echo esc_html( $st_jo_school['postcode'] . ' ' . $st_jo_school['city'] ); ?><br>
				</address>
			</section>
			<section>
				<ul>
					<li>
						<a href="tel:<?php echo esc_attr( $st_jo_school['phone_tel'] ); ?>" class="no-underline"><img
								src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/smartphone.svg' ) ); ?>"
								alt=""><?php echo esc_html( $st_jo_school['phone'] ); ?></a>
					</li>
					<li>
						<a href="mailto:<?php echo esc_attr( $st_jo_school['email'] ); ?>"><img
								src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/speak.svg' ) ); ?>"
								alt="">Nous écrire ›</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/infos-pratiques/' ) ); ?>"><img
								src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/talk.svg' ) ); ?>"
								alt="">Infos
							pratiques ›</a>
					</li>
				</ul>
			</section>
			<section>
				<h2 class="estjo-footer__title">Suivez l’animation de l’école et les prochains évènements sur le Facebook de l’Apel !</h2>
				<ul>
					<li>
						<a href="<?php echo esc_url( $st_jo_school['facebook'] ); ?>" target="_blank" rel="noopener"><img
								src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/facebook.svg' ) ); ?>"
								alt="">Facebook de l’APEL</a>
					</li>
				</ul>
			</section>
		</div>
		<div class="estjo-footer__copyright">
			<ul>
				<li>
					©2025 École Saint-Joseph
				</li>
				<li>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Mentions légales</a>
				</li>
				<li>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Données personnelles</a>
				</li>
			</ul>
		</div>
	</div>
</footer>