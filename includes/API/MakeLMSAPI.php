<?php

namespace WOOLMS\API;

use WOOLMS\Classes\Tasks;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class to handle REST API routes for the Books.
 */
class MakeLMSAPI extends WP_REST_Controller{
    /**
     * Namespace for the REST API routes.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Constructor.
     *
     * Sets up the namespace for the REST API routes.
     */
    public function __construct() {
        $this->namespace = 'tasktodo/v1';
    }

    /**
     * Registers REST API routes for the Books custom post type.
     *
     * This function defines and registers multiple REST API endpoints for managing
     * books, including routes for retrieving all books, a single book, creating,
     * editing, deleting, and searching for books. Each route is defined with its
     * respective HTTP method, callback function, permission check, arguments, and schema.
     */
    public function register_routes() {
        // Register route for retrieving all books
        register_rest_route(
            $this->namespace,
            '/books',
            [
                [
                    'methods'             => WP_REST_Server::READABLE, // HTTP GET method
                    'callback'            => [ $this, 'LMS_get_books' ], // Callback function to get books
                    'permission_callback' => [ $this, 'get_item_permissions_check' ], // Permission check callback
                    'args'                => [ $this->get_collection_params()], // Arguments for the route
                    'schema'              => [ $this, 'get_books_schema' ], // Schema definition for the response
                ],
            ]
        );

        // Register route for retrieving a single book
        register_rest_route(
            $this->namespace,
            '/book',
            [
                [
                    'methods'             => WP_REST_Server::READABLE, // HTTP GET method
                    'callback'            => [ $this, 'LMS_get_single_book' ], // Callback function to get a single book
                    'permission_callback' => [ $this, 'get_item_permissions_check' ], // Permission check callback
                    'args'                => [ $this->get_collection_params()], // Arguments for the route
                ],
            ]
        );

        // Register route for creating a new book
        register_rest_route(
            $this->namespace,
            '/createtask',
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE, // HTTP POST method
                    'callback'            => [ $this, 'LMS_create_books' ], // Callback function to create a book
                    'permission_callback' => [ $this, 'get_item_permissions_check' ], // Permission check callback
                    'args'                => [ $this->get_collection_params()], // Arguments for the route
                ],
            ]
        );

        // Register route for editing an existing book
        register_rest_route(
            $this->namespace,
            '/editbook',
            [
                [
                    'methods'             => WP_REST_Server::EDITABLE, // HTTP PUT/PATCH method
                    'callback'            => [ $this, 'LMS_edit_books' ], // Callback function to edit a book
                    'permission_callback' => [ $this, 'get_item_permissions_check' ], // Permission check callback
                    'args'                => [ $this->get_collection_params()], // Arguments for the route
                ],
            ]
        );

        // Register route for deleting an existing book
        register_rest_route(
            $this->namespace,
            '/deletebook',
            [
                [
                    'methods'             => WP_REST_Server::DELETABLE, // HTTP DELETE method
                    'callback'            => [ $this, 'LMS_delete_books' ], // Callback function to delete a book
                    'permission_callback' => [ $this, 'get_item_permissions_check' ], // Permission check callback
                    'args'                => [ $this->get_collection_params()], // Arguments for the route
                ],
            ]
        );

        // Register route for searching books
        register_rest_route(
            $this->namespace,
            '/search',
            [
                [
                    'methods'             => WP_REST_Server::READABLE, // HTTP GET method
                    'callback'            => [ $this, 'LMS_search_books' ], // Callback function to search books
                    'permission_callback' => [ $this, 'get_item_permissions_check' ], // Permission check callback
                    'args'                => [ $this->get_collection_params()], // Arguments for the route
                ],
            ]
        );
    }

    /**
     * Retrieves the schema for the book object.
     *
     * This schema defines the structure and data types of the book object, including
     * the properties such as book ID, author, ISBN, publication date, publisher, and title.
     * The schema is used for validating the data and providing metadata for the book object
     * in the REST API.
     *
     * @return array The JSON schema for the book object.
     */
    public function get_books_schema() {

        return [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'book',
            'type'       => 'object',
            'properties' => [
                'book_id' => [
                    'description' => 'The unique identifier for the book.',
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                    'readonly'    => true,
                ],
                'author' => [
                    'description' => 'The author of the book.',
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                ],
                'isbn' => [
                    'description' => 'The ISBN of the book.',
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                ],
                'publication_date' => [
                    'description' => 'The publication date of the book.',
                    'type'        => 'string',
                    'format'      => 'date',
                    'context'     => [ 'view', 'edit' ],
                ],
                'publisher' => [
                    'description' => 'The publisher of the book.',
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                ],
                'title' => [
                    'description' => 'The title of the book.',
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                ],
            ],
        ];
    }

    /**
     * Retrieves a single book by its ID.
     *
     * This function is the callback for the REST API route to get a single book.
     * It retrieves the book data based on the provided ID.
     *
     * @param WP_REST_Request $request The REST API request object.
     * @return WP_REST_Response The REST API response object.
     */
    public function LMS_get_single_book( $request ){

        $data = $request->get_params();
        $post_id = isset( $data['id'] ) ? sanitize_text_field( $data['id'] ) : "";
        $data = Tasks::LMS_get_single_book( $post_id );

        return new WP_REST_Response( $data, 200 );
    }

    /**
     * Retrieves all books.
     *
     * This function is the callback for the REST API route to get all books.
     * It retrieves the books data, caches it, and sorts the books by book ID in descending order.
     *
     * @return WP_REST_Response The REST API response object.
     */
    public function LMS_get_books( $request ){

        $data = $request->get_params();
        $page = isset( $data['page'] ) ? intval( $data['page'] ) : 1; // Default page 1 if not provided
        $limit = isset( $data['limit'] ) ? intval( $data['limit'] ) : 10; // Default limit 10 if not provided

        error_log( print_r( [ '$limit' => $limit ], true ) );

        $books = get_transient('LMS_books_cached' );
//        error_log( print_r( [ '$books' => $books ], true ) );
        if ( $books === false || ( is_array( $books ) && count( $books ) < 1) ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'books';
            $books = $wpdb->get_results("SELECT `book_id`,`author`, `isbn`, `publication_date`, `publisher`, `title` FROM $table_name", ARRAY_A);
            set_transient('LMS_books_cached', $books, 12 * HOUR_IN_SECONDS);
        }

        usort($books, function( $a, $b ) {
            return $b['book_id'] <=> $a['book_id'];
        });

        $pages_in_ary = [];
        $total_books = count( $books );
        $total_pages = ceil( $total_books / $limit );
        if( $total_pages > 1 ){
            for( $i= 1; $i<= $total_pages; $i++ ){
                $pages_in_ary[] = $i;
            }
        }

        $offset = ($page - 1) * $limit;
        $books = array_slice( $books, $offset, $limit);

        $result = array(
          'data' =>   $books,
          'total_pages' =>   $total_pages,
          'pages_in_ary' =>   $pages_in_ary,
        );

        return new WP_REST_Response( $result, 200 );
    }

    /**
     * Creates a new book.
     *
     * This function is the callback for the REST API route to create a new book.
     * It retrieves the book data from the request and passes it to the Tasks::create_book function.
     *
     * @param WP_REST_Request $request The REST API request object.
     * @return WP_REST_Response The REST API response object.
     */
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

    /**
     * Edits an existing book.
     *
     * This function is the callback for the REST API route to edit a book.
     * It retrieves the book data from the request and passes it to the Tasks::LMS_update_book function.
     *
     * @param WP_REST_Request $request The REST API request object.
     * @return WP_REST_Response The REST API response object.
     */
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

    /**
     * Deletes an existing book.
     *
     * This function is the callback for the REST API route to delete a book.
     * It retrieves the book ID from the request and passes it to the Tasks::LMS_delete_book function.
     *
     * @param WP_REST_Request $request The REST API request object.
     * @return WP_REST_Response The REST API response object.
     */
    public function LMS_delete_books( $request ){

        $data = $request->get_params();
        $post_id = isset( $data['id'] ) ? sanitize_text_field( $data['id'] ) : "";
        $is_delete = Tasks::LMS_delete_book( $post_id );
        $smg = $is_delete['$message'];

        return new WP_REST_Response( $smg, 200 );
    }

    /**
     * Searches for books based on a search value.
     *
     * This function is the callback for the REST API route to search books.
     * It retrieves the search value from the request and passes it to the Tasks::search_books function.
     *
     * @param WP_REST_Request $request The REST API request object.
     * @return WP_REST_Response The REST API response object.
     */
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