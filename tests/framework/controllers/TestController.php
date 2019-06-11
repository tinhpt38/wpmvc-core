<?php

namespace UnitTesting\Controllers;

use WPMVC\MVC\Controller;

class TestController extends Controller
{
    public function action($var)
    {
        echo $var;
    }
    public function filter($var)
    {
        return $var;
    }
}