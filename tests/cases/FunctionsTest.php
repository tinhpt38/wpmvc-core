<?php

use WPMVC\Resolver;
use PHPUnit\Framework\TestCase;

/**
 * Tests global functions.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 3.1.12
 */
class FunctionsTest extends TestCase
{
    /**
     * Tests assets_url() global function.
     * @group functions
     */
    function testAssetUrl()
    {
        $this->assertEquals(
            'http://localhost/phpunit/wp-content/plugins/my-plugin/assets/img/wpmvc.png',
            assets_url('img/wpmvc.png', 'C:/phpunit/wp-content/plugins/my-plugin/app/Controllers/FakeController.php')
        );
        $this->assertEquals(
            'http://localhost/phpunit/wp-content/themes/my-theme/assets/img/wpmvc.png',
            assets_url('img/wpmvc.png', 'C:/phpunit/wp-content/themes/my-theme/app/Controllers/FakeController.php')
        );
    }
    /**
     * Tests get_wp_home_path() global function.
     * @group functions
     */
    function testHomePathUrl()
    {
        $this->assertEquals('C:\\temp\\phpunit\\', get_wp_home_path());
    }
    /**
     * Tests exists_bridge() global function.
     * @group functions
     */
    function testExistsBridge()
    {
        $this->assertTrue(function_exists('exists_bridge'));
        $this->assertFalse(exists_bridge('other'));
    }
    /**
     * Tests get_bridge() global function.
     * @group functions
     */
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
    /**
     * Tests theme_view() global function.
     * @group functions
     */
    function testThemeView()
    {
        $this->assertTrue(function_exists('theme_view'));
    }
    /**
     * Tests theme_view() global function.
     * @group functions
     * @group compatibility
     * @group wpml
     * @require function assets_url
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testAssetsUrlWpml()
    {
        // Prepare
        define('ICL_LANGUAGE_CODE', 'es');
        // Run
        $url = assets_url( 'img/wpmvc.png', 'C:/phpunit/wp-content/plugins/my-plugin/app/Controllers/FakeController.php');
        // Test
        $this->assertEquals('http://dev.example.com/es/', home_url('/'));
        $this->assertEquals('http://dev.example.com/wp-content/plugins/my-plugin/assets/img/wpmvc.png', $url);
    }
    /**
     * Tests theme_view() global function.
     * @group functions
     * @group compatibility
     * @group wpml
     * @require function assets_url
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testAssetsUrlWpmlWithDomainConflic()
    {
        // Prepare
        define('ICL_LANGUAGE_CODE', 'de');
        // Run
        $url = assets_url( 'img/wpmvc.png', 'C:/phpunit/wp-content/plugins/my-plugin/app/Controllers/FakeController.php');
        // Test
        $this->assertEquals('http://dev.example.com/de/', home_url('/'));
        $this->assertEquals('http://dev.example.com/wp-content/plugins/my-plugin/assets/img/wpmvc.png', $url);
    }
    /**
     * Tests theme_view() global function.
     * @group functions
     * @group compatibility
     * @group wpml
     * @require function assets_url
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testAssetsUrlWpmlWithoutUrlChange()
    {
        // Prepare
        define('ICL_LANGUAGE_CODE', 'en');
        // Run
        $url = assets_url( 'img/wpmvc.png', 'C:/phpunit/wp-content/plugins/my-plugin/app/Controllers/FakeController.php');
        // Test
        $this->assertEquals('http://dev.example.com/', home_url('/'));
        $this->assertEquals('http://dev.example.com/wp-content/plugins/my-plugin/assets/img/wpmvc.png', $url);
    }
}