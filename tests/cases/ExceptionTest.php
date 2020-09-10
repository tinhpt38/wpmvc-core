<?php

use WP_Error;
use WPMVC\Exceptions\WPException;
use PHPUnit\Framework\TestCase;
/**
 * Tests exception classes.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 3.1.14
 */
class ExceptionTest extends TestCase
{
    /**
     * Test exception with WP_Error as param.
     * @since 3.1.14
     * @group exceptions
     */
    public function testWpError()
    {
        // Prepare
        $wp_error = new WP_Error( 101, 'A WordPress error' );
        // Run
        $exception = new WPException( $wp_error );
        // Assert
        $this->assertInstanceOf(WP_Error::class, $exception->get_wp_error());
        $this->assertEquals('A WordPress error', $exception->getMessage());
        $this->assertEquals(101, $exception->getCode());
    }
    /**
     * Test exception with WP_Error as param and a string error code.
     * @since 3.1.14
     * @group exceptions
     */
    public function testWpErrorStringCode()
    {
        // Prepare
        $wp_error = new WP_Error( 'c101', 'Error code' );
        // Run
        $exception = new WPException( $wp_error );
        // Assert
        $this->assertInstanceOf(WP_Error::class, $exception->get_wp_error());
        $this->assertEquals('Error code', $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }
    /**
     * Test exception with no WP error as param.
     * @since 3.1.14
     * @group exceptions
     */
    public function testNoWpError()
    {
        // Prepare
        $exception = new WPException( 'No WordPress', 707 );
        // Run
        $wp_error = $exception->get_wp_error();
        // Assert
        $this->assertInstanceOf(WP_Error::class, $wp_error);
        $this->assertEquals('No WordPress', $exception->getMessage());
        $this->assertEquals(707, $exception->getCode());
        $this->assertEquals('No WordPress', $wp_error->get_error_message());
        $this->assertEquals(707, $wp_error->get_error_code());
    }
}