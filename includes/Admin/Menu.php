<?php

namespace WOOLMS\Admin;

/**
 * Class Menu
 *
 * Handles the creation of a custom admin menu for managing the Library System.
 */
class Menu{

    function  __construct() {
        if( is_admin() ) {
            add_action( 'admin_menu', [ $this, 'my_plugin_menu' ] );
        }
    }

    /**
     * Registers the custom menu page in the WordPress admin menu.
     */
    public function my_plugin_menu(){
        add_menu_page(
            __( 'Manage Library System', 'manage-library-system' ),
            __( 'Manage Library System', 'manage-library-system'), 'manage_options',
            'manage-library-system',
            [ $this, 'manage_library_system' ],
            'dashicons-book-alt',
            '2.1' );
    }

    /**
     * Callback function to render the content of the custom menu page.
     */
    public function manage_library_system() {

        require_once WOOLMS_PATH . 'templates/app.php';
    }

}