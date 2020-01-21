<?php

use WPMVC\Resolver;
use PHPUnit\Framework\TestCase;

/**
 * Tests global functions.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 3.1.0
 */
class FunctionsTest extends TestCase
{
    function testAssetUrl()
    {
        $this->assertEquals(
            assets_url('img/wpmvc.png', 'C:/phpunit/wp-content/plugins/my-plugin/app/Controllers/FakeController.php'),
            'http://localhost/phpunit/wp-content/plugins/my-plugin/assets/img/wpmvc.png'
        );
        $this->assertEquals(
            assets_url('img/wpmvc.png', 'C:/phpunit/wp-content/themes/my-theme/app/Controllers/FakeController.php'),
            'http://localhost/phpunit/wp-content/themes/my-theme/assets/img/wpmvc.png'
        );
    }
    function testHomePathUrl()
    {
        $this->assertEquals('C:\\temp\\phpunit\\', get_wp_home_path());
    }
    function testExistsBridge()
    {
        $this->assertTrue(function_exists('exists_bridge'));
        $this->assertFalse(exists_bridge('other'));
    }
    function testGetBridge()
    {
        // Prepare
        $test = new stdClass;
        $test->id = 7;
        // Exec
        Resolver::add('test', $test);
        // Assert
        $this->assertTrue(function_exists('get_bridge'));
        $this->assertInstanceOf(stdClass::class, get_bridge('test'));
        $this->assertNotNull(get_bridge('test'));
        $this->assertEquals(7, get_bridge('test')->id);
    }
    function testThemeView()
    {
        $this->assertTrue(function_exists('theme_view'));
    }
}