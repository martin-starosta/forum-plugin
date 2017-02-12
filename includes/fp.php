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

/**
 * Main class for Forum Plugin
 */

 require_once (__DIR__ . '/jobs/jobs.php');

class Forum {

	/**
	 * Class constructor
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'fp_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'fp_deactivate' ) );

		add_action( 'init', array( $this, 'fp_create_taxonomies' ) );
		add_action( 'init', array( $this, 'create_jobposttype' ) );
		add_action( 'init', array( $this, 'fp_run' ) );
	}

	/**
	 * Function initialize plugin and call init functions.
	 */
	public function fp_run() {
		//Create job (TEST)
		$jobs = new Jobs();
		$jobs->init();
	}

	/**
	 * Function is called on plugin activation.
	 */
	public function fp_activate() {
		echo 'Forum Plugin has been activated';
	}

	/**
	 * Function is called on plugin deactivation.
	 */
	public function fp_deactivate() {
		echo 'Forum Plugin has been deactivated';
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

	function create_jobposttype() {
		$labels = array (
			'name' => 'Pracovné pozície',
			'singular_name' => 'Pracovná pozícia',
			'menu_name' => 'Pracovné pozície',
			'parent_item_colon' => 'Hlavná pozícia',
			'all_items' => 'Všetky pozície',
			'view_item' => 'Zobraziť pracovnú pozíciu',
			'add_new_item' => 'Pridať novú prac. pozíciu',
			'add_new' => 'Pridať novú',
			'edit_item' => 'Upraviť pozíciu',
			'update_item' => 'Aktualizovať pozíciu',
			'search_items' => 'Hľadať pracovnú pozíciu',
			'not_found' => 'Pracovná pozícia sa nenašla',
			'not_found_in_trash' => 'Pozícia sa nenašla v koši',
		);
		
		$args = array (
			'label' => 'Pracovná pozícia',
			'description' => 'Voľné pracovné pozície pre KAM-ov.',
			'labels' => $labels,
			'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 
			'comments', 'revisions', 'custom-fields', ),
			'taxonomies' => array( 'job_category' ),
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'menu_position' => 5,
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'capability_type' => 'post',
		);

		register_post_type( 'jobs', $args );
	}
}
