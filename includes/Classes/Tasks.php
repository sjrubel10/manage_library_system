<?php

namespace WOOLMS\Classes;

use WP_REST_Response;
use WOOLMS\Traits\HandleTransient;

/**
 * Class Tasks
 *
 * Provides methods for CRUD operations related to books using WordPress database.
 */
class Tasks {

    use HandleTransient;
    function __construct(){
        //code
    }

    /**
     * Creates a new book entry in the database.
     *
     * @param string $title The title of the book.
     * @param string $publisher The publisher of the book.
     * @param string $publication_date The publication date of the book.
     * @param string $isbn The ISBN of the book.
     * @param string $author The author of the book.
     * @return int|false The ID of the inserted book or false on failure.
     */
    public static function create_book( $title, $publisher, $publication_date, $isbn, $author ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'books';
        $wpdb->insert(
            $table_name,
            [
                'title' => sanitize_text_field( $title ),
                'author' => sanitize_text_field( $author ),
                'publisher' => sanitize_text_field( $publisher ),
                'isbn' => sanitize_text_field( $isbn ),
                'publication_date' => sanitize_text_field( $publication_date ),
            ]
        );

        if ( $wpdb->insert_id ) {
//            $curd_data[] = HandleTransient::get_book_by_book_id( $wpdb->insert_id );
            self::get_compare_transient_value_with_curd_data( $wpdb->insert_id, 'add_book' );
            $result = $wpdb->insert_id;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Updates an existing book entry in the database.
     *
     * @param int $book_id The ID of the book to update.
     * @param string $title The new title of the book.
     * @param string $author The new author of the book.
     * @param string $publisher The new publisher of the book.
     * @param string $isbn The new ISBN of the book.
     * @param string $publication_date The new publication date of the book.
     * @return bool|int True on success, false on failure.
     */
    public static function LMS_update_book( $book_id, $title, $author, $publisher, $isbn, $publication_date ) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'books';
        $updated = $wpdb->update(
            $table_name,
            [
                'title' => $title,
                'author' => $author,
                'publisher' => $publisher,
                'isbn' => $isbn,
                'publication_date' => $publication_date,
            ],
            ['book_id' => intval($book_id)]
        );

        if ($updated) {
            self::get_compare_transient_value_with_curd_data( $book_id, 'edit_book' );
            return $updated;
        } else {
            return false;
        }
    }

    /**
     * Deletes a book entry from the database.
     *
     * @param int $book_id The ID of the book to delete.
     * @return array An array with 'success' boolean and 'message' string.
     */
    public static function LMS_delete_book( $book_id ) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'books';

        // Delete the book record
        $deleted = $wpdb->delete( $table_name, [ 'book_id' => intval( $book_id ) ] );

        // Check if the delete operation was successful
        if ($deleted) {
            $success = true;
            $message = 'Book deleted successfully.';
            self::get_compare_transient_value_with_curd_data( $book_id, 'delete' );
        } else {
            $success = false;
            $message = 'Book could not be deleted.';
        }

        $result = array(
            'success' => $success,
            '$message' => $message,
        );

        // Return the response
        return $result;
    }

    /**
     * Searches for books in the database based on title.
     *
     * @param string $query The search query string.
     * @param int $page The page number for pagination.
     * @param int $per_page The number of results per page.
     * @return array|null Array of book objects or null if no results.
     */
    public static function search_books( $query, $page = 1, $per_page = 10) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'books';
        $offset = ($page - 1) * $per_page;

        $search_query = $wpdb->prepare(
            "SELECT * FROM $table_name WHERE title LIKE %s LIMIT %d OFFSET %d",
            '%' . $wpdb->esc_like($query) . '%',
            $per_page,
            $offset
        );

        return $wpdb->get_results($search_query);
    }

    /**
     * Retrieves a single book from the database by its ID.
     *
     * @param int $book_id The ID of the book to retrieve.
     * @return WP_REST_Response|null The book object or null if not found.
     */
    public static function LMS_get_single_book( $book_id ) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'books';
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE book_id = %d", $book_id );
        $book =  $wpdb->get_row($query);

        return new WP_REST_Response( $book, 200 );
    }

}