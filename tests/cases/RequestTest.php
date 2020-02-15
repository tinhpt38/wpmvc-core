<?php

use WPMVC\Request;
use PHPUnit\Framework\TestCase;

/**
 * Tests request class.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 3.1.11
 */
class RequestTest extends TestCase
{
    /**
     * Test request data from $_POST.
     * @group request
     */
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
    /**
     * Test request data from $_GET.
     * @group request
     */
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
    /**
     * Test request data from $wp_queru->query_vars.
     * @group request
     */
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
    /**
     * Test default.
     * @group request
     */
    function testDefault()
    {
        // Prepare & run
        $return = Request::input('val', 123);
        // Assert
        $this->assertInternalType('int', $return);
        $this->assertEquals(123, $return);
    }
    /**
     * Test value clear.
     * @group request
     */
    function testClear()
    {
        // Prepare
        $_GET['val'] = 123;
        // Run
        $return = Request::input('val', null, true);
        // Assert
        $this->assertFalse(isset($_GET['val']));
    }
    /**
     * Test single value sanititation sanitization.
     * @group request
     * @group sanitization
     * @dataProvider providerSanitization
     * 
     * @param string $value
     * @param string $expected_type
     * @param mixed  $expected
     */
    function testSanitization($value, $expected_type, $expected)
    {
        // Prepare
        $key = 'val';
        $_GET[$key] = $value;
        // Run
        $return = Request::input($key);
        // Assert
        $this->assertInternalType($expected_type, $return);
        $this->assertEquals($expected, $return);
    }
    /**
     * Test single value sanititation sanitization.
     * @group request
     * @group sanitization
     * @dataProvider providerNoSanitization
     * 
     * @param string $value
     * @param string $expected
     */
    function testNoSanitization($value, $expected)
    {
        // Prepare
        $key = 'val';
        $_GET[$key] = $value;
        // Run
        $return = Request::input($key, null, false, false);
        // Assert
        $this->assertInternalType('string', $return);
        $this->assertEquals($expected, $return);
    }
    /**
     * Test single value sanititation sanitization.
     * @group request
     * @group sanitization
     * @dataProvider providerArraySanitization
     * 
     * @param string $value
     * @param mixed  $array_index
     * @param string $expected_type
     * @param mixed  $expected
     */
    function testArraySanitization($value, $array_index, $expected_type, $expected)
    {
        // Prepare
        $key = 'val';
        $_GET[$key] = $value;
        // Run
        $return = Request::input($key);
        // Assert
        $this->assertInternalType('array', $return);
        $this->assertInternalType($expected_type, $return[$array_index]);
        $this->assertEquals($expected, $return[$array_index]);
    }
    /**
     * Test all.
     * @group request
     */
    function testAll()
    {
        global $wp_query;
        $wp_query->query_vars['bool'] = 'true';
        $_GET['val'] = '123';
        $_POST['string'] = 'test';
        // Run
        $return = Request::all();
        // Assert
        $this->assertInternalType('array', $return);
        $this->assertEquals(123, $return['val']);
        $this->assertEquals('test', $return['string']);
        $this->assertEquals(true, $return['bool']);
    }
    /**
     * Returns data sets for test.
     * @see self::testSanitization
     */
    function providerSanitization()
    {
        return [
            [' A string ', 'string', 'A string'],
            ['False', 'bool', false],
            ['TRUE', 'bool', true],
            ['123', 'int', 123],
            ['3.45', 'float', 3.45],
            ['test@email.com', 'string', 'test@email.com'],
        ];
    }
    /**
     * Returns data sets for test.
     * @see self::testNoSanitization
     */
    function providerNoSanitization()
    {
        return [
            [' A string ', ' A string '],
            ['False', 'False'],
            ['123', '123'],
            ['3.45', '3.45'],
        ];
    }
    /**
     * Returns data sets for test.
     * @see self::testArraySanitization
     */
    function providerArraySanitization()
    {
        return [
            [['1','2'], 1, 'int', 2],
            [['1',' A string ','2'], 1, 'string', 'A string'],
            [['1','2','True'], 2, 'bool', true],
            [['123'], 0, 'int', 123],
            [['3.56'], 0, 'float', 3.56],
        ];
    }
}