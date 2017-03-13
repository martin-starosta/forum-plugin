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
require_once( __DIR__ . '/../helpers/datehelper.php' );

/**
 * Main class for handling profesia tasks
 */
class Profesia {

	const PROFESIA_XML_FEED_URL = 'http://www.profesia.sk/partner/export.php?auth=d4445ddb73312c3b60da8d7b301ce7da';
	const JOB_POST_TYPE = 'jobs';
	const IMPORT_MAX_DAYS_OLD = 1;

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
		$this->xml_feed = simplexml_load_file( self::PROFESIA_XML_FEED_URL );
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

		$types = $this->read_job_types( $this->xml );
		foreach ( $types as $type ) {
			$this->create_term( 'job_type', $type[0] );
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
	 * Reads job types from XML.
	 *
	 * @param SimpleXML $xml XML object with Profesia codes.
	 */
	public function read_job_types( $xml ) {
		return $xml->jobtypes->profesia->jobtype->type;
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
		}
	}

	/**
	 * Function creates new custom post type: job
	 *
	 * @param SimpleXML $job_offer SimpleXML Object for job offer from Profesia XML feed.
	 */
	public function create_profesia_job( $job_offer ) {
		$author_id = 1;

		$title = $job_offer->position[0];
		$slug = StringHelper::seo_friendly_text( $title );

		$categories = StringHelper::get_simplexmls_as_strings( $job_offer->offercategories->offercategory );
		$positions = StringHelper::get_simplexmls_as_strings( $job_offer->offerpositions->offerposition );
		$types = StringHelper::get_simplexmls_as_strings( $job_offer->jobtypes->jobtype );
		$types = $this->convert_job_type_ID_to_string( $types );
		$external_id = (string) $job_offer['id'];
		$last_updated = (string) $job_offer->last_updated;

		$date = DateTime::createFromFormat('Y-m-d H:i:s', $last_updated);
		if( DateHelper::is_older_than_days( $date, self::IMPORT_MAX_DAYS_OLD ) === true ) {
			// Job post is older than max. number of days, so not importing
			return;
		}

		$page = get_page_by_title( $title, OBJECT, self::JOB_POST_TYPE );
		$posts = get_posts(array(
			'numberposts'	=> 1,
			'post_type'		=> 'jobs',
			'meta_key'		=> 'external_id',
			'meta_value'	=> $external_id,
		));

		if ( null === $page && sizeof( $posts ) === 0 ) {
			$post_id = wp_insert_post(
				array(
					'post_author' => $author_id,
					'post_name' => $slug,
					'post_title' => $title,
					'post_date' => $last_updated,
					'post_status' => 'publish',
					'post_type' => self::JOB_POST_TYPE,
				)
			);

			if ($post_id) {
				// insert post meta
				add_post_meta($post_id, 'company', (string) $job_offer->company );
				add_post_meta($post_id, 'url', (string) $job_offer->url );
				add_post_meta($post_id, 'location', (string) $job_offer->location );
				add_post_meta($post_id, 'external_id', $external_id );
				add_post_meta($post_id, 'source', 'PROFESIA' );
				add_post_meta($post_id, 'featured', 'false' );
				wp_set_post_terms( $post_id, $categories, 'job_category' );
				wp_set_post_terms( $post_id, $positions, 'job_position' );
				wp_set_post_terms( $post_id, $types, 'job_type' );
			}
		} else {
			//TODO: Job already exists. Update or do nothing?
		}
	}

	private function convert_job_type_ID_to_string( $job_types ) {
		if( !is_array( $job_types ) ) {
			$job_types = [$job_types];
		}

		return array_map( function( $id ) {
			switch( $id) {
				case 1: return "plný úväzok";
				case 2: return "skrátený úväzok";
				case 4: return "na dohodu (brigády)";
				case 8: return "živnosť";
				case 32: return "internship, stáž";
				default: return "";
			}
		}, $job_types );
	}
}
