<?php
/**
 * Block Patterns
 *
 * @package st-jo
 */

/**
 * Register block patterns
 */
function st_jo_register_block_patterns() {
	/*
	 * Sample media for the patterns below.
	 *
	 * These used to be six files that never existed -- sample-image.jpg,
	 * sample-icon.png, sample-round-image*.jpg -- behind a hardcoded
	 * `/wp-content/themes/st-jo/theme/assets/...` path carrying one `theme/`
	 * segment too many. Inserting any of those patterns produced broken
	 * images. They now point at assets the theme actually ships, through the
	 * function that knows where the theme lives; an editor replaces them from
	 * the media library like any other image.
	 */
	$sample_photo = esc_url( get_theme_file_uri( 'assets/images/enfants-jouant-a-l-ecole.jpg' ) );
	$sample_icon  = esc_url( get_theme_file_uri( 'assets/icons/calendar.png' ) );

	// Register button patterns category
	register_block_pattern_category(
		'st-jo-buttons',
		array( 'label' => __( 'Boutons', 'st-jo' ) )
	);
	
	// Register custom components category
	register_block_pattern_category(
		'st-jo-components',
		array( 'label' => __( 'Composants St-Jo', 'st-jo' ) )
	);
	
	// Image masquée pattern
	register_block_pattern(
		'st-jo/image-masque-dessinee',
		array(
			'title'       => __( 'Image masquée dessinée', 'st-jo' ),
			'description' => __( 'Une image affichée à travers une forme SVG irrégulière', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'media' ),
			'keywords'    => array( 'image', 'masque', 'dessiné', 'svg', 'forme' ),
			'content'     => '<!-- wp:html -->
<div class="carousel-masked-image-wrapper">
	<div class="carousel-masked-image with-splash with-character" style="--carousel-image-url: url(\'' . $sample_photo . '\');">
		<div class="splash-overlay"></div>
		<div class="character-body"></div>
		<div class="character-head"></div>
	</div>
</div>
<!-- /wp:html -->',
		)
	);

	// Primary button pattern
	register_block_pattern(
		'st-jo/button-primary',
		array(
			'title'       => __( 'Bouton principal', 'st-jo' ),
			'description' => __( 'Un bouton principal avec l\'apparence par défaut', 'st-jo' ),
			'categories'  => array( 'st-jo-buttons', 'buttons' ),
			'content'     => '<!-- wp:html -->
' . st_jo_button( array(
	'text' => __( 'Cliquez ici', 'st-jo' ),
	'url'  => '#',
	'echo' => false,
) ) . '
<!-- /wp:html -->',
		)
	);

	// Secondary button pattern
	register_block_pattern(
		'st-jo/button-secondary',
		array(
			'title'       => __( 'Bouton secondaire', 'st-jo' ),
			'description' => __( 'Un bouton secondaire', 'st-jo' ),
			'categories'  => array( 'st-jo-buttons', 'buttons' ),
			'content'     => '<!-- wp:html -->
' . st_jo_button( array(
	'text'    => __( 'En savoir plus', 'st-jo' ),
	'url'     => '#',
	'variant' => 'secondary',
	'echo'    => false,
) ) . '
<!-- /wp:html -->',
		)
	);



	// Button group pattern
	register_block_pattern(
		'st-jo/button-group',
		array(
			'title'       => __( 'Groupe de boutons', 'st-jo' ),
			'description' => __( 'Plusieurs boutons alignés', 'st-jo' ),
			'categories'  => array( 'st-jo-buttons', 'buttons' ),
			'content'     => '<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group">
<!-- wp:html -->
' . st_jo_button( array(
	'text' => __( 'Action principale', 'st-jo' ),
	'url'  => '#',
	'echo' => false,
) ) . '
<!-- /wp:html -->

<!-- wp:html -->
' . st_jo_button( array(
	'text'    => __( 'Action secondaire', 'st-jo' ),
	'url'     => '#',
	'variant' => 'secondary',
	'echo'    => false,
) ) . '
<!-- /wp:html -->
</div>
<!-- /wp:group -->',
		)
	);

	// CTA section with button pattern
	register_block_pattern(
		'st-jo/cta-section',
		array(
			'title'       => __( 'Section appel à l\'action', 'st-jo' ),
			'description' => __( 'Une section avec titre, texte et bouton', 'st-jo' ),
			'categories'  => array( 'st-jo-buttons', 'call-to-action' ),
			'content'     => '<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"backgroundColor":"gray-50","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-gray-50-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">' . __( 'Prêt à nous rejoindre ?', 'st-jo' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">' . __( 'Découvrez notre école et inscrivez votre enfant pour la prochaine rentrée.', 'st-jo' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="text-align: center; margin-top: 2rem;">
' . st_jo_button( array(
	'text' => __( 'Nous contacter', 'st-jo' ),
	'url'  => '/contact',
	'size' => 'large',
	'echo' => false,
) ) . '
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->',
		)
	);

	// MEA Rouge pattern (simple)
	register_block_pattern(
		'st-jo/mea-rouge',
		array(
			'title'       => __( 'MEA Rouge', 'st-jo' ),
			'description' => __( 'Cartouche rouge sobre et centré', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'text' ),
			'keywords'    => array( 'mea', 'rouge', 'cartouche', 'mise en avant', 'red' ),
			'content'     => '<!-- wp:group {"className":"is-style-mea-rouge"} -->
<div class="wp-block-group is-style-mea-rouge">
<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		)
	);

	// MEA Rouge with Content pattern
	register_block_pattern(
		'st-jo/mea-rouge-with-content',
		array(
			'title'       => __( 'MEA Rouge avec contenu', 'st-jo' ),
			'description' => __( 'Cartouche rouge avec mise en page 33/66 en desktop', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'text' ),
			'keywords'    => array( 'mea', 'rouge', 'cartouche', 'mise en avant', 'contenu', 'colonnes' ),
			'content'     => '<!-- wp:group {"className":"is-style-mea-rouge is-style-mea-rouge-with-content"} -->
<div class="wp-block-group is-style-mea-rouge is-style-mea-rouge-with-content">
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%">
<!-- wp:group {"className":"is-style-icon-title"} -->
<div class="wp-block-group is-style-icon-title">
<!-- wp:image {"width":64,"height":64} -->
<figure class="wp-block-image is-resized"><img src="' . $sample_icon . '" alt="" width="64" height="64"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . __( 'Titre principal', 'st-jo' ) . '</h3>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%">
<!-- wp:paragraph -->
<p>' . __( 'Votre texte de mise en avant ici. Ce cartouche rouge attire l\'attention sur des informations importantes avec un fort contraste visuel.', 'st-jo' ) . '</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->',
		)
	);

	// Icon Title pattern
	register_block_pattern(
		'st-jo/icon-title',
		array(
			'title'       => __( 'Icône et titre', 'st-jo' ),
			'description' => __( 'Icône au-dessus du titre en desktop, à côté en mobile', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'text' ),
			'keywords'    => array( 'icon', 'titre', 'icône' ),
			'content'     => '<!-- wp:group {"className":"is-style-icon-title"} -->
<div class="wp-block-group is-style-icon-title">
<!-- wp:image {"width":64,"height":64} -->
<figure class="wp-block-image is-resized"><img src="' . $sample_icon . '" alt="" width="64" height="64"/></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . __( 'Titre avec icône', 'st-jo' ) . '</h3>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->',
		)
	);

	// Single Round Image Link pattern
	register_block_pattern(
		'st-jo/round-image-link-single',
		array(
			'title'       => __( 'Lien image ronde', 'st-jo' ),
			'description' => __( 'Photo ronde avec bouton jaune en chevauchement', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'media' ),
			'keywords'    => array( 'image', 'rond', 'lien', 'bouton', 'photo' ),
			'content'     => '<!-- wp:group {"className":"is-style-round-image-link"} -->
<div class="wp-block-group is-style-round-image-link">
<!-- wp:image {"width":250,"height":250} -->
<figure class="wp-block-image is-resized"><img src="' . $sample_photo . '" alt="" width="250" height="250"/></figure>
<!-- /wp:image -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-chevron-right"} -->
<div class="wp-block-button is-style-chevron-right"><a class="wp-block-button__link wp-element-button">' . __( 'Découvrir', 'st-jo' ) . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
		)
	);

	// Two Round Image Links pattern
	register_block_pattern(
		'st-jo/round-image-links-x2',
		array(
			'title'       => __( 'Deux liens images rondes', 'st-jo' ),
			'description' => __( 'Deux photos rondes côte à côte avec boutons', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'media' ),
			'keywords'    => array( 'image', 'rond', 'lien', 'bouton', 'photo', 'duo' ),
			'content'     => '<!-- wp:group {"className":"is-style-round-image-links-container"} -->
<div class="wp-block-group is-style-round-image-links-container">
<!-- wp:group {"className":"is-style-round-image-link"} -->
<div class="wp-block-group is-style-round-image-link">
<!-- wp:image {"width":250,"height":250} -->
<figure class="wp-block-image is-resized"><img src="' . $sample_photo . '" alt="" width="250" height="250"/></figure>
<!-- /wp:image -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-chevron-right"} -->
<div class="wp-block-button is-style-chevron-right"><a class="wp-block-button__link wp-element-button">' . __( 'Découvrir', 'st-jo' ) . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-round-image-link"} -->
<div class="wp-block-group is-style-round-image-link">
<!-- wp:image {"width":250,"height":250} -->
<figure class="wp-block-image is-resized"><img src="' . $sample_photo . '" alt="" width="250" height="250"/></figure>
<!-- /wp:image -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-chevron-right"} -->
<div class="wp-block-button is-style-chevron-right"><a class="wp-block-button__link wp-element-button">' . __( 'Explorer', 'st-jo' ) . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
		)
	);

	// Three Round Image Links pattern
	register_block_pattern(
		'st-jo/round-image-links-x3',
		array(
			'title'       => __( 'Trois liens images rondes', 'st-jo' ),
			'description' => __( 'Trois photos rondes alignées avec boutons', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'media' ),
			'keywords'    => array( 'image', 'rond', 'lien', 'bouton', 'photo', 'trio' ),
			'content'     => '<!-- wp:group {"className":"is-style-round-image-links-container"} -->
<div class="wp-block-group is-style-round-image-links-container">
<!-- wp:group {"className":"is-style-round-image-link"} -->
<div class="wp-block-group is-style-round-image-link">
<!-- wp:image {"width":250,"height":250} -->
<figure class="wp-block-image is-resized"><img src="' . $sample_photo . '" alt="" width="250" height="250"/></figure>
<!-- /wp:image -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-chevron-right"} -->
<div class="wp-block-button is-style-chevron-right"><a class="wp-block-button__link wp-element-button">' . __( 'Découvrir', 'st-jo' ) . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-round-image-link"} -->
<div class="wp-block-group is-style-round-image-link">
<!-- wp:image {"width":250,"height":250} -->
<figure class="wp-block-image is-resized"><img src="' . $sample_photo . '" alt="" width="250" height="250"/></figure>
<!-- /wp:image -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-chevron-right"} -->
<div class="wp-block-button is-style-chevron-right"><a class="wp-block-button__link wp-element-button">' . __( 'Explorer', 'st-jo' ) . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-round-image-link"} -->
<div class="wp-block-group is-style-round-image-link">
<!-- wp:image {"width":250,"height":250} -->
<figure class="wp-block-image is-resized"><img src="' . $sample_photo . '" alt="" width="250" height="250"/></figure>
<!-- /wp:image -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-chevron-right"} -->
<div class="wp-block-button is-style-chevron-right"><a class="wp-block-button__link wp-element-button">' . __( 'Visiter', 'st-jo' ) . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
		)
	);

	// MEA Jaune pattern
	register_block_pattern(
		'st-jo/mea-jaune',
		array(
			'title'       => __( 'MEA Jaune', 'st-jo' ),
			'description' => __( 'Cartouche jaune sobre et centré', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'text' ),
			'keywords'    => array( 'mea', 'jaune', 'cartouche', 'mise en avant', 'yellow' ),
			'content'     => '<!-- wp:group {"className":"is-style-mea-jaune"} -->
<div class="wp-block-group is-style-mea-jaune">
<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		)
	);

	// Feuille quadrillée pattern
	register_block_pattern(
		'st-jo/feuille-quadrillee',
		array(
			'title'       => __( 'Feuille quadrillée', 'st-jo' ),
			'description' => __( 'Conteneur avec fond quadrillé façon feuille de cahier', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'text' ),
			'keywords'    => array( 'feuille', 'quadrillé', 'grille', 'cahier', 'grid', 'paper' ),
			'content'     => '<!-- wp:group {"className":"is-style-feuille-quadrillee"} -->
<div class="wp-block-group is-style-feuille-quadrillee">
<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		)
	);

	// MEA Quadrillée pattern
	register_block_pattern(
		'st-jo/mea-quadrillee',
		array(
			'title'       => __( 'MEA Quadrillée', 'st-jo' ),
			'description' => __( 'Feuille quadrillée avec décorations crayon et gomme', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'text' ),
			'keywords'    => array( 'mea', 'quadrillé', 'grille', 'cahier', 'crayon', 'gomme', 'grid', 'pencil', 'eraser' ),
			'content'     => '<!-- wp:group {"className":"is-style-mea-quadrillee"} -->
<div class="wp-block-group is-style-mea-quadrillee">
<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		)
	);

	/*
	 * One news item, as a single group.
	 *
	 * Until now an item on the news page was a run of loose paragraphs between
	 * two horizontal rules: nothing tied a title to its own text, so removing an
	 * expired item meant deleting the right paragraphs one by one and hoping.
	 * Wrapped in a group, the item is selected, moved or deleted in one gesture
	 * -- which is what makes the page maintainable by someone who does not write
	 * code.
	 *
	 * The heading is a real h3 under the month's h2. The page previously jumped
	 * from h1 straight to h4 with the item titles as bold paragraphs, which
	 * leaves screen readers without an outline to navigate.
	 */
	register_block_pattern(
		'st-jo/actualite',
		array(
			'title'       => __( 'Actualité', 'st-jo' ),
			'description' => __( 'Une actualité : titre, date et texte, dans un bloc qui se déplace et se supprime d\'un seul geste', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'text' ),
			'keywords'    => array( 'actualité', 'actu', 'événement', 'news', 'agenda' ),
			'content'     => '<!-- wp:group {"className":"is-style-actualite"} -->
<div class="wp-block-group is-style-actualite">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . __( '🎄 Titre de l\'actualité', 'st-jo' ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"actualite-date"} -->
<p class="actualite-date">' . __( 'Vendredi 21 novembre, à partir de 17 h', 'st-jo' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>' . __( 'Décrivez ici l\'événement en quelques phrases : ce que c\'est, où il a lieu, et pour qui.', 'st-jo' ) . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		)
	);

	/*
	 * A whole month of news, ready to fill in. Inserting this once a month and
	 * deleting the previous one is the maintenance routine the page is missing.
	 */
	register_block_pattern(
		'st-jo/actualites-mois',
		array(
			'title'       => __( 'Actualités du mois', 'st-jo' ),
			'description' => __( 'Un titre de mois suivi de deux actualités à compléter', 'st-jo' ),
			'categories'  => array( 'st-jo-components', 'text' ),
			'keywords'    => array( 'actualité', 'actu', 'mois', 'agenda', 'news' ),
			'content'     => '<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">' . __( 'Mois 2026', 'st-jo' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"is-style-actualite"} -->
<div class="wp-block-group is-style-actualite">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . __( '🎄 Première actualité', 'st-jo' ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"actualite-date"} -->
<p class="actualite-date">' . __( 'Vendredi 21 novembre, à partir de 17 h', 'st-jo' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>' . __( 'Décrivez ici l\'événement en quelques phrases : ce que c\'est, où il a lieu, et pour qui.', 'st-jo' ) . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-actualite"} -->
<div class="wp-block-group is-style-actualite">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . __( '🍫 Seconde actualité', 'st-jo' ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"actualite-date"} -->
<p class="actualite-date">' . __( 'Commandes jusqu\'au 21 novembre', 'st-jo' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>' . __( 'Décrivez ici l\'événement en quelques phrases : ce que c\'est, où il a lieu, et pour qui.', 'st-jo' ) . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		)
	);
}
add_action( 'init', 'st_jo_register_block_patterns' );