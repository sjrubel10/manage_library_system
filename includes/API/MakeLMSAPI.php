<?php

namespace WOOLMS\API;

use WOOLMS\Classes\Tasks;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class MakeLMSAPI extends WP_REST_Controller{
    function __construct(){
        $this->namespace = 'tasktodo/v1';
    }

    public function register_routes(){
        register_rest_route(
            $this->namespace,
            '/books',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'LMS_get_books' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [ $this->get_collection_params()],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/book',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'LMS_get_single_book' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [ $this->get_collection_params()],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/createtask',
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'LMS_create_books' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [ $this->get_collection_params()],
                ],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/editbook',
            [
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'LMS_edit_books' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [ $this->get_collection_params()],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/deletebook',
            [
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [ $this, 'LMS_delete_books' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [ $this->get_collection_params()],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/search',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'LMS_search_books' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [ $this->get_collection_params()],
                ],
            ]
        );


    }

    public function LMS_get_single_book( $request ){

        $data = $request->get_params();
        $post_id = isset( $data['id'] ) ? sanitize_text_field( $data['id'] ) : "";
        $data = Tasks::LMS_get_single_book( $post_id );

        return new WP_REST_Response( $data, 200 );
    }

    function LMS_get_books() {
        $books = get_transient('LMS_books_cached' );
        if ( $books === false || ( is_array( $books ) && count( $books ) < 1) ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'books';
            $books = $wpdb->get_results("SELECT `book_id`,`author`, `isbn`, `publication_date`, `publisher`, `title` FROM $table_name" );
            set_transient('LMS_books_cached', $books, 12 * HOUR_IN_SECONDS);
        }

        return new WP_REST_Response( $books, 200 );
    }

    public function LMS_create_books( $request ){

        $data = $request->get_params(); // Get JSON data from the request
        $title = isset( $data['title'] ) ? sanitize_text_field( $data['title'] ) : "";
        $publisher = isset( $data['publisher'] ) ? sanitize_text_field( $data['publisher'] ) : "";
        $publication_date = isset( $data['publication_date'] ) ? sanitize_text_field( $data['publication_date'] ) : "";
        $isbn = isset( $data['isbn'] ) ? sanitize_text_field( $data['isbn'] ) : "";
        $author = isset( $data['author'] ) ? sanitize_text_field( $data['author'] ) : "";

        $insert_id = Tasks::create_book( $title, $publisher, $publication_date, $isbn, $author );
        if( $insert_id ){
            $smg = 'Book Successfully Created';
        }else{
            $smg = 'Bool Create Failed';
        }

        return new WP_REST_Response( $smg, 200 );
    }

    public function LMS_edit_books( $request ){

        $data = $request->get_params(); // Get JSON data from the request
        $title = isset( $data['title'] ) ? sanitize_text_field( $data['title'] ) : "";
        $book_id = isset( $data['boo_id'] ) ? sanitize_text_field( $data['boo_id'] ) : "";
        $publisher = isset( $data['publisher'] ) ? sanitize_text_field( $data['publisher'] ) : "";
        $author = isset( $data['author'] ) ? sanitize_text_field( $data['author'] ) : "";
        $isbn = isset( $data['isbn'] ) ? sanitize_text_field( $data['isbn'] ) : "";
        $publication_date = isset( $data['publication_date'] ) ? sanitize_text_field( $data['publication_date'] ) : "";

        $insert_id = Tasks::LMS_update_book( $book_id, $title, $author, $publisher, $isbn, $publication_date );
        if( $insert_id ){
            $smg = 'Book Successfully Updated';
        }else{
            $smg = 'Book Update Failed';
        }

        return new WP_REST_Response( $smg, 200 );
    }

    public function LMS_delete_books( $request ){

        $data = $request->get_params();
        $post_id = isset( $data['id'] ) ? sanitize_text_field( $data['id'] ) : "";
        $is_delete = Tasks::LMS_delete_book( $post_id );
        $smg = $is_delete['$message'];

        return new WP_REST_Response( $smg, 200 );
    }

    public function LMS_search_books( $request ){

        $data = $request->get_params();
        $search_value = isset( $data['searchValue'] ) ? sanitize_text_field( $data['searchValue'] ) : '';
        $limit = isset( $data['limit'] ) ? sanitize_text_field( $data['limit'] ) : '';
        $page = isset( $data['limit'] ) ? sanitize_text_field( $data['page'] ) : '';

        $result = Tasks::search_books( $search_value, $page = 1, $limit );

        return new WP_REST_Response( $result, 200 );
    }

    /**
     * Checks if the current user has permissions to access the item.
     *
     * This function checks if the current user has the 'manage_options' capability,
     * which typically grants access to manage options and settings within WordPress.
     *
     * @param WP_REST_Request $request The request object.
     * @return bool Whether the current user has permissions to access the item.
     */
    public function get_item_permissions_check( $request ){

        if( current_user_can( 'manage_options' ) ){
            return true;
        }

        return false;
    }



}