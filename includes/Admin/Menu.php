<?php

namespace WOOLMS\Admin;

class Menu{

    function  __construct() {
        if( is_admin() ) {
            add_action('admin_menu', [$this, 'my_plugin_menu']);
        }
    }

    public function my_plugin_menu(){
        add_menu_page(
            __( 'Manage Library System', 'manage-library-system'),
            __( 'Manage Library System', 'manage-library-system'), 'manage_options',
            'manage-library-system',
            [$this, 'manage_lybrary_system'],
            'dashicons-book-alt',
            '2.1' );
    }

    public function manage_lybrary_system() {
        require_once WOOLMS_PATH . 'templates/app.php';
    }

}