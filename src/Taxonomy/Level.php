<?php
/**
 * Matador Jobs Custom Extension for Etage0 / Taxonomy
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

use stdClass;
use matador\Matador;
use matador\Bullhorn_Import;
use matador\MatadorJobsCustomEtage0\Extension;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Taxonomy
 *
 * @final
 * @since 1.0.0
 */
final class Level {

	/**
	 * Class Constructor
	 *
	 * Adds shortcodes to WP.
	 */
	public function __construct() {
		add_filter( 'matador_variable_job_taxonomies', [ __CLASS__, 'add_taxonomy' ] );
		add_filter( 'matador_taxonomy_args', [ __CLASS__, 'show_in_menu' ], 10, 2 );
		add_filter( 'matador_bullhorn_import_fields', [ __CLASS__, 'add_import_fields' ] );
		add_action( 'matador_bullhorn_import_save_job', [ __CLASS__, 'save_levels_taxonomy_terms' ], 10, 3 );
	}

	/**
	 * Add Level Taxonomy
	 *
	 * @since 1.0.0
	 *
	 * @param array $taxonomies
	 *
	 * @return array
	 */
	public static function add_taxonomy( array $taxonomies ) : array {

		$taxonomies['level'] = array(
			'key'    => 'matador-levels',
			'single' => _x( 'career level', 'Career Level Singular Name.', 'matador-extension-custom-etage0' ),
			'plural' => _x( 'career levels', 'Career Levels Plural Name.', 'matador-extension-custom-etage0' ),
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
	public static function show_in_menu( array $args, string $key ) : array {

		if ( 'level' !== $key ) {
			return $args;
		}

		$args['show_in_menu'] = true;

		return $args;
	}

	/**
	 * Add Import Fields
	 *
	 * This is called by the 'matador_import_fields' to add fields to the job import
	 * function of the @see Bullhorn_Import::get_jobs() behavior so we can use the data
	 * later.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	public static function add_import_fields( array $fields ) : array {
		$fields['customText12'] = [
			'type'   => 'string',
			'saveas' => 'core',
			'name'   => 'career_level'
		];
		return $fields;
	}

	/**
	 * Save Levels Taxonomy Term
	 *
	 * @since 1.0.0
	 *
	 * @param stdClass $job
	 * @param int $wpid
	 * @param Bullhorn_Import $bullhorn
	 *
	 * @return void
	 */
	public static function save_levels_taxonomy_terms( stdClass $job, int $wpid, Bullhorn_Import $bullhorn ) : void {

		if ( ! is_object( $job ) || ! is_int( $wpid ) || ! is_object( $bullhorn ) ) {
			return;
		}

		if ( ! empty( $job->customText12 ) ) {

			$value = $job->customText12;

			$taxonomy = Matador::variable( 'level', 'job_taxonomies' );

			wp_set_object_terms( $wpid, $value, $taxonomy['key'] );
		}
	}
}
