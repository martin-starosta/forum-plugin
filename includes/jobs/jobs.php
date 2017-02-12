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

/**
 * Main class for handling job posts
 */
 require_once('profesia.php');

class Jobs {
    const JOB_POST_TYPE = 'jobs';

    public function init() {
        $this->create_job_categories_taxonomy();
        $this->create_job_positions_taxonomy();

        //TODO: Zavolaj pri zapnuti pluginu
        $profesia = new Profesia();
        $profesia->init();
    }

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

    public function createJob() {
        $post_id = -1;
        $author_id = 1;
        $slug = 'example-job';
        $title = 'Programatically created job';

        //TODO: Check by position ID?
        if ( null === get_page_by_title( $title, OBJECT, self::JOB_POST_TYPE) ) {
            //Create job post
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
                add_post_meta($post_id, 'kraj', 'Žilinský kraj');
            }
        } else {
            //Job post already exists
            $post_id = -2;
        }
    }
}