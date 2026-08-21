<?php
/**
 * The school's own facts, in one place.
 *
 * Its name, address, phone number and public links appeared twice: written into
 * the footer template, and written again into the structured data. Two copies of
 * a postal address drift the day one of them is corrected -- and here the drift
 * would be published as a contradiction between what a visitor reads and what a
 * search engine is told, which is precisely what the structured data exists to
 * prevent.
 *
 * The spellings below are the ones the Éducation nationale directory holds for
 * UAI 0351195J, cross-checked against the town hall's own page. When they change,
 * they change here.
 *
 * @package st-jo
 */

/**
 * Returns the school's identity and contact details.
 *
 * @return array<string, mixed>
 */
function st_jo_school() {
	static $school = null;

	if ( null === $school ) {
		$school = array(
			'name'      => 'École Saint-Joseph',
			'uai'       => '0351195J',
			'street'    => 'Allée Henri Queffélec',
			'postcode'  => '35340',
			'city'      => 'La Bouëxière',
			'region'    => 'Bretagne',
			'country'   => 'FR',
			// Displayed as typed; `tel:` and schema.org want their own forms.
			'phone'     => '02 99 62 63 09',
			'phone_tel' => '0299626309',
			'phone_e164' => '+33299626309',
			'email'     => 'eco35.st-joseph.la-bouexiere@enseignement-catholique.bzh',
			// Six decimals: the three the directory publishes are below what the
			// structured data guidelines ask for.
			'latitude'  => 48.184514,
			'longitude' => -1.440811,
			'facebook'  => 'https://www.facebook.com/share/19sAwLHJa1/?mibextid=wwXIfr',
			'directory' => 'https://www.education.gouv.fr/annuaire/35340/la-bouexiere',
		);
	}

	return $school;
}
