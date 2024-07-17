<?php

namespace WOOLMS;
use WOOLMS\API\MakeLMSAPI;

class Api
{
    function __construct(){
        add_action( 'rest_api_init', [$this, 'register_api']);
    }

    public function register_api(){
        $tasktodo = new MakeLMSAPI();
        $tasktodo->register_routes();
    }

}