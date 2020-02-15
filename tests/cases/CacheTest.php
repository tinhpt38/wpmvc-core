<?php

use WPMVC\Cache;
use WPMVC\Config;
use WPMVC\PHPFastCache\phpFastCache_instances;
use PHPUnit\Framework\TestCase;

/**
 * Tests cache.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 3.1.6
 */
class CacheTest extends TestCase
{
    /**
     * Testing cache variable.
     * @since 3.1.6
     */
    protected $cache = null;
    /**
     * Cache test setup.
     * @since 3.1.6
     * @group cache
     */
    private function setCache()
    {
        $config = new Config(['cache' => [
            'enabled'       => true,
            'storage'       => 'auto',
            'path'          => TEMP_PATH . '/cache',
            'securityKey'   => '',
            'fallback'      => [
                                'memcache'  =>  'files',
                                'apc'       =>  'sqlite',
                            ],
            'htaccess'      => true,
            'server'        => [],
        ]]);
        $this->cache = new Cache($config);
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testCacheInit()
    {
        // Prepare
        $this->setCache();
        // Assert
        $this->assertInstanceOf(Cache::class, $this->cache);
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testInstance()
    {
        // Assert
        $this->assertInternalType('object', Cache::instance());
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testGetEmpty()
    {
        // Prepare and execute
        $value = Cache::get('empty_value');
        // Assert
        $this->assertNull($value);
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testGetDefault()
    {
        // Prepare and execute
        $value = Cache::get('empty_value', 1);
        // Assert
        $this->assertNotNull($value);
        $this->assertEquals(1, $value);
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testAddGet()
    {
        // Prepare
        Cache::add('test_add_get', 999, 5);
        // Execute
        $value = Cache::get('test_add_get', 1);
        // Assert
        $this->assertNotNull($value);
        $this->assertEquals(999, $value);
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testHas()
    {
        // Prepare and execute
        Cache::add('test_has', 123, 5);
        // Assert
        $this->assertTrue(Cache::has('test_has'));
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testNotHas()
    {
        // Assert
        $this->assertFalse(Cache::has('test_not_has'));
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testForget()
    {
        // Prepare and execute
        Cache::forget('test_has');
        // Assert
        $this->assertFalse(Cache::has('test_has'));
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testRemember()
    {
        // Prepare
        $value = 9;
        // Prepare and execute
        $remembered = Cache::remember('test_remember', 5, function() use(&$value) {
            return $value + 1;
        });
        // Assert
        $this->assertTrue(Cache::has('test_remember'));
        $this->assertEquals(10, $remembered);
        $this->assertEquals($remembered, Cache::get('test_remember'));
    }
    /**
     * Test.
     * @since 3.1.6
     * @group cache
     */
    public function testFlush()
    {
        // Prepare and execute
        Cache::flush();
        // Assert
        $this->assertFalse(Cache::has('test_has'));
        $this->assertFalse(Cache::has('test_remember'));
        $this->assertFalse(Cache::has('test_add_get'));
    }
}