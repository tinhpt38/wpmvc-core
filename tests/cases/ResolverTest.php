<?php

use WPMVC\Resolver;
use PHPUnit\Framework\TestCase;

/**
 * Tests resolver class.
 *
 * @author Cami Mostajo <info@10quality.com>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 3.1.0
 */
class ResolverTest extends TestCase
{
    /**
     * Test resolver.
     * @group resolver
     */
    function testAdd()
    {
        // Prepare
        $test = new stdClass;
        $test->id = 7;
        // Exec
        Resolver::add('test', $test);
        // Assert
        $this->assertInstanceOf(stdClass::class, Resolver::get('test'));
        $this->assertNotNull(Resolver::get('test'));
        $this->assertEquals(7, Resolver::get('test')->id);
    }
    /**
     * Test resolver.
     * @group resolver
     */
    function testGet()
    {
        // Assert
        $this->assertNull(Resolver::get('other'));
    }
    /**
     * Test resolver.
     * @group resolver
     */
    function testExists()
    {
        // Prepare
        $test = new stdClass;
        $test->id = 7;
        // Exec
        Resolver::add('test', $test);
        // Assert
        $this->assertTrue(Resolver::exists('test'));
        $this->assertFalse(Resolver::exists('other'));
    }
}