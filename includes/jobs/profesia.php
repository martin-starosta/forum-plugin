<?php
/**
 * Filename:  profesia.php
 *
 * @author    Martin Starosta<martin.starosta83@gmail.com>
 * @copyright 2017 Mayorsoft.eu
 * @license   GPL
 * @package   FP/Jobs
 * @see       https://kamforum.sk
 */

require_once( __DIR__ . '/../helpers/stringhelper.php' );

/**
 * Main class for handling profesia tasks
 */
class Profesia {

	const JOB_POST_TYPE = 'jobs';

	/**
	 * XML contains Profesia book codes (lists).
	 *
	 * @var SimpleXML $xml XML object loaded from file
	 */
	private $xml;

	/**
	 * XML contains Profesia job offers.
	 *
	 * @var SimpleXML $xml_feed XML object loaded from Profesia feed
	 */
	private $xml_feed;

	/**
	 * Class constructor. Loads XML file to XML object variable.
	 */
	public function __construct() {
		$this->xml = simplexml_load_file( plugin_dir_path( __FILE__ ) . 'lists.xml' );
		$this->xml_feed = simplexml_load_file( plugin_dir_path( __FILE__ ) . 'profesia-export.xml' );
	}

	/**
	 * Function initialize taxonomies for Profesia Jobs.
	 */
	public function init() {
		$categories = $this->read_categories( $this->xml );
		foreach ( $categories as $category ) {
			$this->create_term( 'job_category', $category[0] );
		}

		$positions = $this->read_positions( $this->xml );
		foreach ( $positions as $position ) {
			$this->create_term( 'job_position', $position[0] );
		}

		$this->create_job_posts_from_profesia_feed();
	}

	/**
	 * Reads job categories from XML.
	 *
	 * @param SimpleXML $xml XML object with Profesia codes.
	 */
	public function read_categories( $xml ) {
		return $xml->categories->category;
	}

	/**
	 * Reads job positions from XML.
	 *
	 * @param SimpleXML $xml XML object with Profesia codes.
	 */
	public function read_positions( $xml ) {
		return $xml->positions->position;
	}

	/**
	 * Function creates taxonomy term in Wordpress.
	 *
	 * @param String $taxonomy Name of the taxonomy.
	 * @param String $title Title of the term.
	 */
	public function create_term( $taxonomy, $title ) {
		$term = term_exists( $taxonomy, $title );
		if ( null === $term ) {
			wp_insert_term(
				$title,
				$taxonomy,
				array(
					'description' => $title,
					'slug' => StringHelper::seo_friendly_text( $title ),
				)
			);
		}
	}

	/**
	 * Get job offers from Profesia XML feed.
	 *
	 * @param SimpleXML $xml XML object with Profesia offers.
	 */
	public function get_offers( $xml ) {
		return $xml->list->offer;
	}

	public function create_job_posts_from_profesia_feed() {
		$offers = $this->get_offers( $this->xml_feed );
		foreach( $offers as $offer) {
			$this->create_profesia_job( $offer );
			return;
		}
	}

	/**
	 * Function creates new custom post type: job
	 *
	 * @param SimpleXML $job_offer SimpleXML Object for job offer from Profesia XML feed.
	 */
	public function create_profesia_job( $job_offer ) {
		$post_id = -1;
		$author_id = 1;

		$title = $job_offer->position[0];
		$slug = StringHelper::seo_friendly_text( $title );

		$categories = StringHelper::get_simplexmls_as_strings( $job_offer->offercategories->offercategory );
		$positions = StringHelper::get_simplexmls_as_strings( $job_offer->offerpositions->offerposition );
		// TODO: Check by position ID?
		if ( null === get_page_by_title( $title, OBJECT, self::JOB_POST_TYPE ) ) {
			$post_id = wp_insert_post(
				array(
					'post_author' => $author_id,
					'post_name' => $slug,
					'post_title' => $title,
					'post_status' => 'publish',
					'post_type' => self::JOB_POST_TYPE,
				)
			);

			if ($post_id) {
				// insert post meta
				//add_post_meta($post_id, 'company', (string) $job_offer->company->innerNode );
				wp_set_post_terms( $post_id, $categories, 'job_category' );
				wp_set_post_terms( $post_id, $positions, 'job_position' );
			}
		} else {
			// Do nothing because job already exists in DB
		}
	}
}
