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

/**
 * Main class for handling profesia tasks
 */
class Profesia {

    private $xml;

    public function __construct() {
        $this->xml = simplexml_load_file(plugin_dir_path( __FILE__ ) . 'lists.xml');
    }

    public function init() {
        $categories = $this->readCategories();
        foreach($categories as $category) {
            $this->createTerm( 'job_category', $category[0], $category['id'][0] );
        }

        $positions = $this->readPositions();
        foreach($positions as $position) {
            $this->createTerm( 'job_position', $position[0], $position['id'][0] );
        }
    }

    public function readCategories() {
        return $this->xml->categories->category;
    }

    public function readPositions() {
        return $this->xml->positions->position;
    }

    public function createTerm($taxonomy, $title, $id) {
        $term = term_exists( $taxonomy, $title );
        if ( null === $term) {
            wp_insert_term(
                $title,
                $taxonomy,
                array (
                    'description' => $title,
                    'slug' => $id,
                )
            );
        }
    }
}