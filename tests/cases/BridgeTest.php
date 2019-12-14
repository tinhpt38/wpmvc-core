<?php
/**
 * Tests global functions.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 3.1.8
 */
class BridgeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test bridge constructor.
     */
    function testConstruct()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Assert
        $this->assertInstanceOf(\WPMVC\Config::class, $main->config);
        $this->assertEquals('test', $main->config->get('assert'));
    }
    /**
     * Test dynamic method on controller. Void.
     */
    function testControllerVoidMethod()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Exec
        ob_start();
        $main->{'_c_void_TestController@action'}('void');
        // Assert
        $this->assertEquals('void', ob_get_clean());
    }
    /**
     * Test dynamic method on controller. Return.
     */
    function testControllerReturnMethod()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Exec
        $return = $main->{'_c_return_TestController@filter'}(123);
        // Assert
        $this->assertEquals(123, $return);
    }
    /**
     * Test dynamic method on view. Void.
     */
    function testViewVoidMethod()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Exec
        ob_start();
        $main->{'_v_void_view@view'}();
        // Assert
        $this->assertEquals('Test View', ob_get_clean());
    }
    /**
     * Test dynamic method on view. Return.
     */
    function testViewReturnMethod()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Exec
        $return = $main->{'_v_return_view@view'}();
        // Assert
        $this->assertEquals('Test View', $return);
    }
    /**
     * Tests parameter override.
     */
    function testControllerParameterOverride()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        $main->add_action( 'test', 'TestController@filter', ['override'] );
        // Exec
        $return = $main->{'_c_return_TestController@filter'}(123);
        // Assert
        $this->assertEquals('override', $return);
    }
    /**
     * Tests view parameter override.
     */
    function testViewParameterOverride()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        $main->add_action( 'test', 'view@view', ['param', 'id'] );
        // Exec
        ob_start();
        $main->{'_v_void_view@view'}('title', 123);
        // Assert
        $this->assertEquals('<h1>title</h1><h2>123</h2>Test View', ob_get_clean());
    }
    /**
     * Test method.
     */
    function testViewMethod()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Exec
        ob_start();
        $main->view('view', ['param' => 'view()']);
        // Assert
        $this->assertEquals('<h1>view()</h1>Test View', ob_get_clean());
    }
    /**
     * Test method.
     */
    function testGetViewMethod()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Exec
        $html = $main->get_view('view', ['param' => 'gat_view()']);
        // Assert
        $this->assertEquals('<h1>gat_view()</h1>Test View', $html);
    }
}