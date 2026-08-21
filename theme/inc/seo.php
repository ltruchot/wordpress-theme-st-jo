<?php
/**
 * Search engine visibility
 *
 * Everything here answers one question: can a family looking for
 * "école La Bouëxière" find this school? Two things stand in the way, and
 * neither is fixed in content.
 *
 * @package st-jo
 */

/**
 * Stops WordPress from serving its own sitemap with a 404 status.
 *
 * The sitemap is generated correctly -- /wp-sitemap.xml returns a complete,
 * valid index of every page. It is the HTTP status that is wrong, and search
 * engines read a 404 as "this does not exist" and move on.
 *
 * Root cause, in core. WP::handle_404() runs before the sitemap is rendered and
 * exempts only is_admin(), is_robots() and is_favicon() -- there is no
 * exemption for is_sitemap(). A sitemap request runs an ordinary post query,
 * this site publishes pages and no posts at all, so the query comes back empty
 * and core sets the 404. WP_Sitemaps::render_sitemaps() then writes the XML and
 * exits without ever putting the status back to 200.
 *
 * The counter-proof: /wp-sitemap.xsl answers 200 on the same site, with the
 * same zero posts. Its route sets `sitemap-stylesheet` instead of `sitemap`, so
 * is_sitemap stays false and handle_404() leaves it alone. And /?sitemap=index
 * returns the same XML with the same 404, which rules out rewrite rules
 * entirely.
 *
 * `pre_handle_404` is the hook core provides for exactly this (since 4.5): it
 * returns before any status is set. This is safe when sitemaps are switched off
 * as well -- in that case render_sitemaps() sets its own 404 later, on
 * template_redirect, and this filter never contradicts it.
 *
 * Known upstream as https://core.trac.wordpress.org/ticket/51912, filed against
 * paginated sitemaps but describing this exact chain: the main query comes back
 * empty, handle_404() sets the status, and template_redirect runs the sitemap
 * logic too late to change it. A site with no posts at all hits it on the very
 * first URL. Until core exempts sitemap requests, the fix belongs in the theme
 * -- never in a patched core file, which the next update would overwrite in
 * silence.
 *
 * @param bool     $preempt  Whether to short-circuit default header handling.
 * @param WP_Query $wp_query The query being handled.
 * @return bool Unchanged, unless this is a sitemap request.
 */
function st_jo_keep_sitemap_status( $preempt, $wp_query ) {
	return $wp_query->is_sitemap ? true : $preempt;
}
add_filter( 'pre_handle_404', 'st_jo_keep_sitemap_status', 10, 2 );

/**
 * Returns the school's structured data, as a schema.org node.
 *
 * `ElementarySchool` is the most specific type schema.org offers for a French
 * école primaire. It is worth being clear about what this does and does not
 * buy: Google publishes no rich result for School or EducationalOrganization,
 * so nothing here changes how the page looks in search results. What it does is
 * tie a name, an address, a phone number and an official identifier to one
 * entity, which is what a "school + town" query has to resolve.
 *
 * Declaring the school as a LocalBusiness would light up a documented rich
 * result, and would be a lie about what this organisation is. We do not.
 *
 * Every value below is also visible to a reader on the page itself: the address
 * and phone number sit in the footer of every page. Marking up content that
 * visitors cannot see is what the structured data guidelines forbid.
 *
 * @return array The ElementarySchool node.
 */
function st_jo_school_schema() {
	return array(
		'@type'         => 'ElementarySchool',
		'@id'           => home_url( '/#school' ),
		'name'          => 'École Saint-Joseph',
		'alternateName' => array(
			'École primaire privée Saint-Joseph',
			'École Saint-Joseph La Bouëxière',
			'École maternelle et élémentaire Saint-Joseph',
		),
		'url'           => home_url( '/' ),
		'logo'          => get_theme_file_uri( 'assets/images/Logotype.png' ),
		'telephone'     => '+33299626309',
		'address'       => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'Allée Henri Queffélec',
			'postalCode'      => '35340',
			'addressLocality' => 'La Bouëxière',
			'addressRegion'   => 'Bretagne',
			'addressCountry'  => 'FR',
		),
		/*
		 * Six decimals. The three published by the Éducation nationale directory
		 * are below what the structured data guidelines ask for.
		 */
		'geo'           => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => 48.184514,
			'longitude' => -1.440811,
		),
		'areaServed'    => array(
			'@type' => 'City',
			'name'  => 'La Bouëxière',
		),
		/*
		 * The UAI is the identifier the French state uses for a school. Google
		 * makes nothing of it, but it is the honest use of `identifier`, and it
		 * is what ties this page to the official directory entry below.
		 */
		'identifier'    => array(
			'@type'      => 'PropertyValue',
			'propertyID' => 'UAI',
			'value'      => '0351195J',
		),
		'sameAs'        => array(
			'https://www.education.gouv.fr/annuaire/35340/la-bouexiere',
			'https://www.facebook.com/share/19sAwLHJa1/?mibextid=wwXIfr',
		),
	);
}

/**
 * Prints the structured data on the home page.
 *
 * The WebSite node is how Google is told which name to display for the site,
 * and which spellings to recognise as the same one. It belongs on the root of
 * the domain and nowhere else, which is why both nodes are printed on the front
 * page only.
 */
function st_jo_print_schema() {
	if ( ! is_front_page() ) {
		return;
	}

	$graph = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			st_jo_school_schema(),
			array(
				'@type'         => 'WebSite',
				'@id'           => home_url( '/#website' ),
				'url'           => home_url( '/' ),
				'name'          => 'École Saint-Joseph',
				'alternateName' => array(
					'École Saint-Joseph La Bouëxière',
					'St-Joseph La Bouëxière',
				),
				'inLanguage'    => 'fr-FR',
				'publisher'     => array( '@id' => home_url( '/#school' ) ),
			),
		),
	);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'st_jo_print_schema' );
