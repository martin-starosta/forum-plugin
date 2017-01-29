<?php

class Forum {

    public function __construct() {
        register_activation_hook( __FILE__, array( $this, 'fp_activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'fp_deactivate' ) );

        add_action('init', array($this,'fp_run'));
    }

    public function fsp_run() {
        /* Nothing implemented yet. */
    }

    public function fsp_activate() {
        echo 'Forum Plugin has been activated';
    }

     public function fps_deactivate() {
        echo 'Forum Plugin has been deactivated';
    }
}
