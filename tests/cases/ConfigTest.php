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
 * @version 3.1.14
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
    /**
     * Test using constantes.
     * @since 3.1.14
     * @group config
     */
    public function testDefinedConstant()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Run
        $test_config = $main->load_config('test');
        // Run & Assert
        $this->assertEquals(123, $test_config->get('test.defined'));
        $this->assertNull($test_config->get('test.not_defined'));
        $this->assertEquals('phpunit', $test_config->get('constants.a'));
        $this->assertEquals('wp-config', $test_config->get('constants.b'));
    }
    /**
     * Test using constantes.
     * @since 3.1.14
     * @group config
     */
    public function testDefinedConstantExceptions()
    {
        // Prepare
        global $config;
        $main = new Main($config);
        // Run
        $test_config = $main->load_config('test');
        // Run & Assert
        $this->assertEquals('phpunit', $test_config->get('namespace'));
        $this->assertEquals('phpunit', $test_config->get('version'));
        $this->assertEquals('phpunit', $test_config->get('type'));
        $this->assertEquals('phpunit', $test_config->get('author'));
        $this->assertEquals('phpunit', $test_config->get('license'));
        $this->assertEquals('phpunit', $test_config->get('addons'));
    }
}