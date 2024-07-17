<?php

namespace WOOLMS;

use WOOLMS\Admin\Enque;
use WOOLMS\Admin\Menu;

class Admin{

    function __construct()
    {
//        include 'functions.php';
        new Menu();
        new Enque();

    }

}