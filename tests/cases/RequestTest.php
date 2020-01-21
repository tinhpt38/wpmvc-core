<?php

use WPMVC\Request;
use PHPUnit\Framework\TestCase;

/**
 * Tests request class.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
class RequestTest extends TestCase
{
    function testPost()
    {
        // Prepare
        $_POST['number'] = 123;
        $_POST['string'] = 'test';
        $_POST['bool'] = true;
        $_POST['array'] = [1,2,3];

        // Assert
        $this->assertEquals(Request::input('number'), 123);
        $this->assertEquals(Request::input('string'), 'test');
        $this->assertEquals(Request::input('bool'), true);
        $this->assertTrue(is_array(Request::input('array')));
        $this->assertEquals(Request::input('array'), [1,2,3]);
    }

    function testGet()
    {
        // Prepare
        $_GET['number'] = 123;
        $_GET['string'] = 'test';
        $_GET['array'] = [1,2,3];

        // Assert
        $this->assertEquals(Request::input('number'), 123);
        $this->assertEquals(Request::input('string'), 'test');
        $this->assertTrue(is_array(Request::input('array')));
        $this->assertEquals(Request::input('array'), [1,2,3]);
    }

    function testQueryVars()
    {
        // Prepare
        global $wp_query;
        $wp_query->query_vars['number'] = 123;
        $wp_query->query_vars['string'] = 'test';
        $wp_query->query_vars['bool'] = true;
        $wp_query->query_vars['array'] = [1,2,3];

        // Assert
        $this->assertEquals(Request::input('number'), 123);
        $this->assertEquals(Request::input('string'), 'test');
        $this->assertEquals(Request::input('bool'), true);
        $this->assertTrue(is_array(Request::input('array')));
        $this->assertEquals(Request::input('array'), [1,2,3]);
    }
}