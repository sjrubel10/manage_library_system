<?php

namespace WOOLMS\Classes;

use WP_REST_Response;

class Tasks
{
    function __construct(){
        //code
    }

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
            HandleTransient :: get_compare_transiend_value_with_curd_data( $wpdb->insert_id, 'add_book' );
            $result = $wpdb->insert_id;
        } else {
            $result = false;
        }

        return $result;
    }

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
            HandleTransient :: get_compare_transiend_value_with_curd_data( $book_id, 'edit_book' );
            return $updated;
        } else {
            return false;
        }
    }

    public static function LMS_delete_book( $book_id ) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'books';

        // Delete the book record
        $deleted = $wpdb->delete( $table_name, [ 'book_id' => intval( $book_id ) ] );

        // Check if the delete operation was successful
        if ($deleted) {
            $success = true;
            $message = 'Book deleted successfully.';
            HandleTransient :: get_compare_transiend_value_with_curd_data( $book_id, 'delete' );
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

    public static function LMS_get_single_book( $book_id ) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'books';
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE book_id = %d", $book_id );
        $book =  $wpdb->get_row($query);

        return new WP_REST_Response( $book, 200 );
    }

    public function get_display_name( $user_id ) {

        if (!$user = get_userdata( $user_id ) )
            return false;

        return $user->data->display_name;
    }


}