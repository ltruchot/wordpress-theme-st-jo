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
	<div class="carousel-masked-image with-splash with-character" style="--carousel-image-url: url(\'/wp-content/themes/st-jo/theme/assets/images/sample-image.jpg\');">
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
}
add_action( 'init', 'st_jo_register_block_patterns' );