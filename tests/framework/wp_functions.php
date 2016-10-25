<?php
/**
 * Wordpress compatibility functions.
 * Emulates wordpress functions for testing purposes.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 1.0.0
 */

function is_wp_error($check)
{
    return is_a($check, 'WP_Error');
}

function wp_upload_dir()
{
    return [
        'path'  => 'C:\\temp\\phpunit\\uploads',
        'url'   => 'http://localhost/phpunit/uploads',
    ];
}

function wp_get_image_editor($path)
{
    return new WP_Image($id);
}

function apply_filters($key, $value)
{
    return $value;
}

function home_url($route = '')
{
    return 'http://localhost/phpunit'.$route;
}

function get_home_path()
{
    return 'C:\\temp\\phpunit\\';
}

function add_action($key, $call) {}

function add_filter($key, $call) {}

function add_shortcode($key, $call) {}

function do_action($key) {}

function register_widget($class) {}

function register_post_type($type, $args) {}

function register_taxonomy() {}

function add_meta_box() {}

function wp_nonce_field() {}

function wp_verify_nonce()
{
    return true;
}