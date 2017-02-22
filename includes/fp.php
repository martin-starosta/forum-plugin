<?php
/**
 * Filename:  fp.php
 *
 * @author    Martin Starosta<martin.starosta83@gmail.com>
 * @copyright 2017 Mayorsoft.eu
 * @license   GPL
 * @package   FP
 * @see       https://kamforum.sk
 */

require_once( __DIR__ . '/jobs/jobs.php' );

/**
 * Main class for Forum Plugin
 */
class Forum {
	/**
	 * Jobs object
	 *
	 * @var Jobs $jobs
	 */
	private $jobs;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->jobs = new Jobs();

		add_action( 'init', array( $this, 'fp_create_taxonomies' ) );
		add_action( 'init', array( $this, 'fp_run' ) );

		add_action( 'daily_profesia_feed', array( $this, 'import_profesia_feed' ) );
	}

	/**
	 * Function initialize plugin and call init functions.
	 */
	public function fp_run() {
		// Register job custom post type and related taxonomies.
		$this->jobs->init();

	}

	/**
	 * Function is called on plugin activation.
	 */
	public function fp_activate() {
		if ( ! wp_next_scheduled( 'daily_profesia_feed' ) ) {
			wp_schedule_event( time(), 'daily', 'daily_profesia_feed' );
		}
	}

	/**
	 * Function is called on plugin deactivation.
	 */
	public function fp_deactivate() {
		wp_clear_scheduled_hook( 'daily_profesia_feed' );
	}

	/**
	 * Function creates taxonomies required for forum.
	 */
	public function fp_create_taxonomies() {
		$this->create_customers_taxonomy();
		$this->create_segments_taxonomy();
	}

	/**
	 * Function registers segments taxonomy.
	 */
	private function create_segments_taxonomy() {
		$labels = array(
			'name' => 'Segmenty',
			'singular_name' => 'Segment',
			'search_items' => 'Hľadať podľa segmentu',
			'all_items' => 'Všetky segmenty',
			'edit_item' => 'Upraviť segment',
			'update_item' => 'Aktualizovať segment',
			'add_new_item' => 'Pridať nový segment',
			'menu_name' => 'Segmenty',
			'view_items' => 'Zobraziť segmenty',
			'popular_items' => 'Najčastejšie používané segmenty',
			'separate_items_with_comas' => 'Oddeľte segmenty čiarkou',
			'add_or_remove_item' => 'Pridať alebo odobrať segment',
			'choose_from_most_used' => 'Vybrať z najčastejšie používaných',
			'not_found' => 'Segment sa nenašiel',
		);

		register_taxonomy(
			'segments',
			'post',
			array(
				'label' => __( 'Segment' ),
				'hierarchical' => true,
				'labels' => $labels,
			)
		);
	}

	/**
	 * Function registers customer taxonomy.
	 */
	private function create_customers_taxonomy() {
		$labels = array(
			'name' => 'Zákazníci',
			'singular_name' => 'Zákazník',
			'search_items' => 'Hľadať podľa zákazníka',
			'all_items' => 'Všetci zákazníci',
			'edit_item' => 'Upraviť zákazníka',
			'update_item' => 'Aktualizovať zákazníka',
			'add_new_item' => 'Pridať nového zákazníka',
			'menu_name' => 'Zákazníci',
			'view_items' => 'Zobraziť zákazníka',
			'popular_items' => 'Najčastejšie používaný zákazníci',
			'separate_items_with_comas' => 'Oddeľte zákazníkov čiarkou',
			'add_or_remove_item' => 'Pridať alebo odobrať zákazníka',
			'choose_from_most_used' => 'Vybrať z najčastejšie používaných',
			'not_found' => 'Zákazník sa nenašiel',
		);

		register_taxonomy(
			'customers',
			'post',
			array(
				'label' => __( 'Customer' ),
				'hierarchical' => true,
				'labels' => $labels,
			)
		);
	}

	/**
	 * Function imports job offers from Profesia XML feed.
	 */
	private function import_profesia_feed() {
		$this->jobs->init_profesia();
	}
}
