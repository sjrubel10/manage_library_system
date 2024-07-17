<?php

namespace WOOLMS\Admin;

class Enque{

    function __construct(){
        $this->init_hooks();
    }

    public function init_hooks(){
        add_action( 'admin_enqueue_scripts', [ $this,'include_all_files' ] );
    }

    public function include_css_files(){
        if( isset( $_GET['page'] ) && $_GET['page'] === 'manage-library-system' ){
            wp_enqueue_style( 'jobplace-style', WOOLMS_LINK . 'build/index.css' );
        }
    }

    public function include_js_files(){
        wp_enqueue_script( 'jobplace-script', WOOLMS_LINK . 'build/index.js', array( 'wp-element' ), '1.0.0', true );
        wp_localize_script('jobplace-script', 'myVars', array(
            'rest_nonce'           => wp_create_nonce( 'wp_rest' ),
            'site_url'           => get_site_url().'/',
        ));
    }

    public function include_all_files(){
        $this->include_css_files();
        $this->include_js_files();
    }

}