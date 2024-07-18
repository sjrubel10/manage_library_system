<?php
/**
 * Plugin Name:       Manage library System
 * Description:       Develop a WordPress plugin for managing a library system that handles book records using custom SQL queries and a REST API.
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.0.1
 * Author:            sjrubel10
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       manage-library-system
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once __DIR__.'/vendor/autoload.php';

new WOOLMS\Api();

/**
 * Class ManageLibrarySystem
 *
 * This class manages the initialization, activation, and setup of the Manage Library System plugin.
 */
final class ManageLibrarySystem {

    /**
     * The plugin version.
     */
    const plugin_version = '1.0.0';

    /**
     * ManageLibrarySystem constructor.
     *
     * Initializes the plugin by defining constants, registering activation hook,
     * and adding action to load plugin components.
     */
    private function __construct() {

        $this->define_constants();
        register_activation_hook( __FILE__, [$this, 'activate']);
        register_deactivation_hook( __FILE__, [$this, 'deactivate'] );
        add_action( 'plugins_loaded', [ $this, 'wooLMS_init_plugin' ] );

    }

    /**
     *Initializes a singleton instance
     * @return ManageLibrarySystem
     **/
    public static function init() {
        static $instance = false;
        if( ! $instance ){
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Initializes the plugin based on context.
     *
     * If in admin context, initializes the admin functionality.
     * For other contexts, could handle additional initialization tasks.
     */
    public function wooLMS_init_plugin() {

        if( is_admin() ) {
            new WOOLMS\Admin();
        }else {
            //echo
        }

    }

    /**
     * Defines plugin-related constants.
     *
     * Defines paths, URLs, plugin name, and version constants.
     */
    public function define_constants() {
        define( 'WOOLMS_PATH', plugin_dir_path(__FILE__ ) );
        define( 'WOOLMS_LINK', plugin_dir_url(__FILE__ ) );
        define( 'WOOLMS_ASSETS_LINK', WOOLMS_LINK . 'assets/' );
        define( 'WOOBLMS_API_LINK', WOOLMS_LINK . 'api/' );
        define( 'WOOLMS_DATA_PATH', WOOLMS_PATH . 'data/' );
        define( 'WOOLMS_PLUGIN_NAME', plugin_basename(__FILE__ ) );
        define( 'WOOLMS_VERSION', self::plugin_version);
        define( 'WOOLMS_admin_ulr', get_admin_url() );
    }

    /**
     * Creates the database table for books on plugin activation.
     *
     * Uses WordPress global $wpdb to create a custom table 'prefix_books' if it doesn't exist.
     */
    public function create_books_table() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'books';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
        book_id mediumint(9) NOT NULL AUTO_INCREMENT,
        title text NOT NULL,
        author text NOT NULL,
        publisher text NOT NULL,
        isbn varchar(20) NOT NULL,
        publication_date DATETIME NOT NULL,
        PRIMARY KEY (book_id)
    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Activates the plugin.
     *
     * Performs activation tasks such as updating version, creating books table if not exists,
     * and setting plugin installation time.
     */
    public function activate() {

        update_option( 'manageLibraryManagement_version', WOOLMS_VERSION );
        global $wpdb;
        $table_name = $wpdb->prefix . "books";
        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
        if ( ! $wpdb->get_var( $query ) == $table_name ) {
            $this->create_books_table();
        }
        $installed = get_option( 'manage_library_management_installed' );

        if( ! $installed ) {
            update_option( 'manage_library_management_installed', time() );
        }
    }

    public function deactivate(){
        delete_transient('LMS_books_cached');
    }
}

/**
 * Initializes the ManageLibrarySystem plugin.
 *
 * @return ManageLibrarySystem Singleton instance of ManageLibrarySystem.
 */
function init_manage_library_system() {

    return ManageLibrarySystem::init();
}

//run plugin
init_manage_library_system();