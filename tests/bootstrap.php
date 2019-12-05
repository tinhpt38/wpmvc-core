<?php
// TESTING BOOTSTRAP
require_once __DIR__.'/../vendor/autoload.php';
// TESTING FRAMEWORK
require_once __DIR__.'/framework/classes/wp_image.php';
require_once __DIR__.'/framework/wp_functions.php';
require_once __DIR__.'/framework/Main.php';
// VENDOR TESTING FRAMEWORK
require_once __DIR__.'/../vendor/10quality/wp-file/tests/framework/class-wp-filesystem.php';
// Globals
define('FRAMEWORK_PATH', __DIR__ . '/framework');
define('TEMP_PATH', FRAMEWORK_PATH . '/.tmp');
define('FS_CHMOD_FILE', '0777');
$wp_query = new stdClass;
$wp_query->query_vars = [];
$config = new \WPMVC\Config([
    'namespace' => 'UnitTesting',
    'paths' => [
                'views'         => __DIR__.'/framework/views/',
                'controllers'   => __DIR__.'/framework/controllers/',
            ],
    'assert'    => 'test',
]);
$wp_filesystem = new WP_Filesystem;