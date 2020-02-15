<?php

use WPMVC\Config;
use PHPUnit\Framework\TestCase;

/**
 * Tests config.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 3.1.11
 */
class ConfigTest extends TestCase
{
    /**
     * Test loading additional configuration.
     * @since 3.1.11
     * @group config
     */
    public function testLoadConfig()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Run
        $test_config = $main->load_config('test');
        // Assert
        $this->assertInstanceOf(Config::class, $test_config);
        $this->assertTrue($test_config->get('test'));
    }
    /**
     * Test loading additional configuration.
     * @since 3.1.11
     * @group config
     */
    public function testLoadConfigVal()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Run & Assert
        $this->assertTrue($main->load_config('test')->get('test'));
    }
}