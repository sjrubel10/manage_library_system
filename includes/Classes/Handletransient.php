<?php

namespace WOOLMS\Classes;

class HandleTransient{

    private $new_transient_data;
    public function __construct(){
//        $this->new_transient_data =
    }

    public static function get_compare_transiend_value_with_curd_data( $book_id, $operation ){

        $get_transient_data = get_transient( 'LMS_books_cached' );

        if( is_array( $get_transient_data ) && count( $get_transient_data ) > 0 ){

            if( $operation === 'add_book' ){

                $curd_data[] = HandleTransient::get_book_by_book_id( $book_id );
                $new_transient_data = array_merge( $get_transient_data, $curd_data );
                set_transient('LMS_books_cached', $new_transient_data, 12 * HOUR_IN_SECONDS);

            }elseif( $operation === 'edit_book' ){

                $curd_data[] = HandleTransient::get_book_by_book_id( $book_id );
                $get_data = self::remove_book_by_id( $get_transient_data, $curd_data[0]['book_id']);
                $new_transient_data = array_merge( $get_data, $curd_data );
                set_transient('LMS_books_cached', $new_transient_data, 12 * HOUR_IN_SECONDS);

            }elseif( $operation === 'delete' ){

                $get_removed_data = self::remove_book_by_id( $get_transient_data, $book_id );
                set_transient('LMS_books_cached', $get_removed_data, 12 * HOUR_IN_SECONDS);

            }
        }


    }

    public static function get_book_by_book_id( $book_id ){

        global $wpdb;
        $table_name = $wpdb->prefix . 'books';
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE book_id = %d", $book_id );
        $book =  $wpdb->get_row( $query, ARRAY_A );

        return $book;
    }

    public static function remove_book_by_id( $transient_data, $book_id ){

        foreach ($transient_data as $key => $book) {
            // Check if the book_id matches, considering both object and array representations
            if (( is_object($book ) && $book->book_id == $book_id ) ||
                ( is_array($book ) && $book['book_id'] == $book_id ) ) {
                unset( $transient_data[ $key ] );
            }
        }

        return array_values( $transient_data );
    }

}