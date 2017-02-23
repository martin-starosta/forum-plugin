<?php
/**
 * Filename:  jobs.php
 *
 * @author    Martin Starosta<martin.starosta83@gmail.com>
 * @copyright 2017 Mayorsoft.eu
 * @license   GPL
 * @package   FP/Jobs
 * @see       https://kamforum.sk
 */

require_once( 'profesia.php' );

/**
 * Main class for handling job posts
 */
class Jobs {

	/**
	 * Function registers job custom post type and related taxonomies.
	 */
	public function init() {
		$this->create_job_categories_taxonomy();
		$this->create_job_positions_taxonomy();
		$this->create_job_types_taxonomy();

		$this->create_jobposttype();

	}

	/**
	 * Function initialize Profesia class and imports job offers from XML feed.
	 */
	public function init_profesia() {
		$profesia = new Profesia();
		$profesia->init();
	}

	/**
	 * Function registers job category taxonomy.
	 */
	public function create_job_categories_taxonomy() {
		$labels = array(
			'name' => 'Kategórie',
			'singular_name' => 'Kategória',
			'search_items' => 'Hľadať podľa kategórie',
			'all_items' => 'Všetky kategórie',
			'edit_item' => 'Upraviť kategóriu',
			'update_item' => 'Aktualizovať kategóriu',
			'add_new_item' => 'Pridať novú kategória',
			'menu_name' => 'Kategórie',
			'view_items' => 'Zobraziť kategórie',
			'popular_items' => 'Najčastejšie používané kategórie',
			'separate_items_with_comas' => 'Oddeľte kategórie čiarkou',
			'add_or_remove_item' => 'Pridať alebo odobrať kategóriu',
			'choose_from_most_used' => 'Vybrať z najčastejšie používaných',
			'not_found' => 'Kategória sa nenašla',
		);

		register_taxonomy(
			'job_category',
			'jobs',
			array(
				'label' => __( 'Kategórie' ),
				'hierarchical' => false,
				'labels' => $labels,
			)
		);
	}

	/**
	 * Function registers job position taxonomy.
	 */
	public function create_job_positions_taxonomy() {
		$labels = array(
			'name' => 'Pozície',
			'singular_name' => 'Pozícia',
			'search_items' => 'Hľadať podľa pozície',
			'all_items' => 'Všetky pozície',
			'edit_item' => 'Upraviť pozíciu',
			'update_item' => 'Aktualizovať pozíciu',
			'add_new_item' => 'Pridať novú pozíciu',
			'menu_name' => 'Pozicie',
			'view_items' => 'Zobraziť pozície',
			'popular_items' => 'Najčastejšie používané pozície',
			'separate_items_with_comas' => 'Oddeľte pozície čiarkou',
			'add_or_remove_item' => 'Pridať alebo odobrať pozíciu',
			'choose_from_most_used' => 'Vybrať z najčastejšie používaných',
			'not_found' => 'Pozícia sa nenašla',
		);

		register_taxonomy(
			'job_position',
			'jobs',
			array(
				'label' => __( 'Pozície' ),
				'hierarchical' => false,
				'labels' => $labels,
			)
		);
	}

	/**
	 * Function registers job custom post type.
	 */
	function create_jobposttype() {
		$labels = array(
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

		$args = array(
			'label' => 'Pracovná pozícia',
			'description' => 'Voľné pracovné pozície pre KAM-ov.',
			'labels' => $labels,
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'author',
				'thumbnail',
				'comments',
				'revisions',
				'custom-fields',
			),
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
			'capability_type' => 'page',
		);

		register_post_type( 'jobs', $args );
	}

	/**
	 * Function registers job types taxonomy.
	 */
	private function create_job_types_taxonomy() {
		$labels = array(
			'name' => 'Typ úväzku',
			'singular_name' => 'Typ úväzku',
			'search_items' => 'Hľadať podľa typu úväzku',
			'all_items' => 'Všetky typy',
			'edit_item' => 'Upraviť typ',
			'update_item' => 'Aktualizovať typ',
			'add_new_item' => 'Pridať nový typ',
			'menu_name' => 'Typ úväzku',
			'view_items' => 'Zobraziť typ',
			'popular_items' => 'Najčastejšie používané typy',
			'separate_items_with_comas' => 'Oddeľte typy čiarkou',
			'add_or_remove_item' => 'Pridať alebo odobrať typ',
			'choose_from_most_used' => 'Vybrať z najčastejšie používaných',
			'not_found' => 'Typ úväzku sa nenašiel',
		);

		register_taxonomy(
			'job_type',
			'jobs',
			array(
				'label' => __( 'JobType' ),
				'hierarchical' => false,
				'labels' => $labels,
			)
		);
	}
}
