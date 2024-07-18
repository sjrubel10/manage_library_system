<?php

namespace WOOLMS;
use WOOLMS\API\MakeLMSAPI;

/**
 * Class Api
 *
 * Initializes and registers REST API routes for the custom API.
 */
class Api{

    /**
     * Constructor.
     *
     * Adds an action hook to initialize the REST API.
     */
    function __construct(){
        add_action( 'rest_api_init', [$this, 'register_api']);
    }

    /**
     * Callback function to register API routes.
     *
     * Creates an instance of the MakeLMSAPI class and registers its routes.
     */
    public function register_api(){
        $tasktodo = new MakeLMSAPI();
        $tasktodo->register_routes();
    }

}