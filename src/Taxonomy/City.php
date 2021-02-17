<?php
/**
 * Matador Jobs Custom Extension for Etage0 / City Taxonomy
 *
 * @link        https://matadorjobs.com/
 * @since       1.0.0
 *
 * @package     Matador Jobs Custom Extension for Etage0
 * @subpackage  Core
 * @author      Matador Software LLC, Jeremy Scott, Paul Bearne
 * @copyright   (c) 2021 Matador Software, LLC
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace matador\MatadorJobsCustomEtage0\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use matador\MatadorJobsCustomEtage0\Extension;

/**
 * Class Taxonomy
 *
 * @final
 * @since 1.0.0
 */
final class City {

	/**
	 * Class Constructor
	 *
	 * Adds shortcodes to WP.
	 */
	public function __construct() {
		add_filter( 'matador_variable_job_taxonomies', [ __CLASS__, 'add_taxonomy' ] );
		add_filter( 'matador_taxonomy_args', [ __CLASS__, 'show_in_menu' ], 10, 2 );
	}

	/**
	 * Add City Taxonomy
	 *
	 * Once added, Matador will detect its presence and populate it during the locations routine.
	 *
	 * @since 1.0.0
	 *
	 * @param array $taxonomies
	 *
	 * @return array
	 */
	public static function add_taxonomy( array $taxonomies ): array {
		$taxonomies['city'] = array(
			'key'    => 'matador-cities',
			'single' => _x( 'city', 'City Location Taxonomy Singular Name.', 'matador-extension-custom-etage0' ),
			'plural' => _x( 'cities', 'City Location Taxonomy Plural Name.', 'matador-extension-custom-etage0' ),
			'slug'   => Extension::setting( 'taxonomy_slug_level' ) ?: 'matador-level',
		);

		return $taxonomies;
	}

	/**
	 * Show in Menu
	 *
	 * Since client is expressedly adding this Taxonomy, show it in menu.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args
	 * @param string $key
	 *
	 * @return array
	 */
	public static function show_in_menu( array $args, string $key ): array {

		if ( 'city' !== $key ) {
			return $args;
		}

		$args['show_in_menu'] = true;

		return $args;
	}
}
