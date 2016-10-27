<?php

use Ayuco\Listener;
use WPMVC\Commands\SetNameCommand;
use WPMVC\Commands\SetupCommand;
use WPMVC\Commands\AddCommand;
use WPMVC\Commands\RegisterCommand;
use WPMVC\Commands\CreateCommand;

/**
 * CORE wordpress functions.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 2.0.4
 */

if ( ! function_exists( 'resize_image' ) ) {
    /**
     * Resizes image and returns URL path.
     * @since 1.0.1
     *
     * @param string  $url    Image URL path
     * @param int     $width  Width wanted.
     * @param int     $height Height wanted.
     * @param boolean $crop   Flag that indicates if resulting image should crop
     *
     * @return string URL
     */
    function resize_image( $url, $width, $height, $crop = true )
    {
        $image = wp_get_image_editor( $url );

        if( is_wp_error( $image ) ) return;

        $image_name = explode( '/', $url );
        $image_name = explode( '.', $image_name[count( $image_name ) - 1] );
        $image_extension = strtolower( $image_name[count( $image_name ) - 1] );
        $image_name = $image_name[0];

        $upload_dir = wp_upload_dir();

        $filename = sprintf(
            '/%s-%sx%s.%s',
            $image_name,
            $width,
            $height,
            $image_extension
        );

        $image->resize( $width, $height, $crop );
        $image->save( $upload_dir['path'] . $filename );

        return $upload_dir['url'] . $filename;
    }
}

if ( ! function_exists( 'assets_url' ) ) {
    /**
     * Returns url of asset located in a theme or plugin.
     * @since 1.0.1
     * @since 2.0.4 Refactored to work with new structure.
     *
     * @param string  $path Asset relative path.
     * @param string  $file File location path.
     *
     * @return string URL
     */
    function assets_url( $path, $file )
    {
        // Preparation
        $route = preg_replace( '/\\\\/', '/', $file );
        $url = apply_filters( 'asset_base_url', rtrim( home_url( '/' ), '/' ) );
        // Clean base path
        $route = preg_replace( '/.+?(?=wp-content)/', '', $route );
        // Clean project relative path
        $route = preg_replace( '/\/app[\/\\A-Za-z0-9\.\\-]+/', '', $route );
        return $url.'/'.apply_filters( 'app_route', $route ).'/assets/'.$path;
    }
}

if ( ! function_exists( 'get_ayuco' ) ) {
    /**
     * Returns ayuco.
     * @since 2.0.3
     * @since 2.0.4 Added new commands.
     *
     * @param string $path Project path.
     *
     * @return object
     */
    function get_ayuco($path)
    {
        $ayuco = new Listener();

        $ayuco->register(new SetNameCommand($path));
        $ayuco->register(new SetupCommand($path));
        $ayuco->register(new AddCommand($path));
        $ayuco->register(new RegisterCommand($path));
        $ayuco->register(new CreateCommand($path));

        return $ayuco;
    }
}

if ( ! function_exists( 'get_wp_home_path' ) )
{
    /**
     * Returns wordpress root path.
     * @since 2.0.4
     *
     * @return string
     */
    function get_wp_home_path()
    {
        return function_exists( 'get_home_path' )
            ? get_home_path()
            : preg_replace( '/wp-content[A-Za-z0-9\.\-\\\_]+/', '', __DIR__ );
    }
}