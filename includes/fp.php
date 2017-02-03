<?php

class Forum {

    public function __construct() {
        register_activation_hook( __FILE__, array( $this, 'fp_activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'fp_deactivate' ) );

        add_action('init', array($this,'fp_run'));
        add_action('init', array($this,'fp_create_taxonomies'));
    }

    public function fp_run() {
        /* Nothing implemented yet. */
    }

    public function fp_activate() {
        echo 'Forum Plugin has been activated';
    }

     public function fp_deactivate() {
        echo 'Forum Plugin has been deactivated';
    }

    /* Private functions */
    public function fp_create_taxonomies() {
        $this->create_customers_taxonomy();
        $this->create_segments_taxonomy();
    }

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
                'label' => __('Segment'),
                'hierarchical' => true,
                'labels' => $labels,
            )
        );
    }

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
                'label' => __('Customer'),
                'hierarchical' => true,
                'labels' => $labels,
            )
        );
    }
}
