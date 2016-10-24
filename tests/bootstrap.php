<?php
// TESTING BOOTSTRAP
require_once __DIR__.'/../vendor/autoload.php';
// TESTING FRAMEWORK
require_once __DIR__.'/framework/classes/wp_image.php';
require_once __DIR__.'/framework/wp_functions.php';
// Globals
$wp_query = new stdClass;
$wp_query->query_vars = [];