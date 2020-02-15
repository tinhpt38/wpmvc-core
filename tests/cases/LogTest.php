<?php

use WPMVC\Log;
use WPMVC\Config;
use WPMVC\KLogger\Logger;
use PHPUnit\Framework\TestCase;

/**
 * Tests log/logger.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 3.1.8
 */
class LogTest extends TestCase
{
    /**
     * Testing logger variable.
     * @since 3.1.6
     */
    protected $logger = null;
    /**
     * Test setup.
     * @since 3.1.6
     */
    private function setLogger()
    {
        $config = new Config(['paths' => [
            'log'           => TEMP_PATH . '/log',
        ]]);
        $this->logger = new Log($config);
    }
    /**
     * Returns concat content inside log files.
     * @since 3.1.6
     */
    private function getContents()
    {
        global $wp_filesystem;
        $content = '';
        foreach(scandir(TEMP_PATH . '/log') as $filename) {
            if (in_array($filename, ['.','..']))
                continue;
            $file = TEMP_PATH . '/log/' . $filename;
            if ($wp_filesystem->is_file($file))
                $content .= $wp_filesystem->get_contents($file);
        }
        return $content;
    }
    /**
     * Removes all files after every test.
     * @since 3.1.6
     */
    protected function tearDown()
    {
        global $wp_filesystem;
        foreach(scandir(TEMP_PATH . '/log') as $filename) {
            $file = TEMP_PATH . '/log/' . $filename;
            if ($wp_filesystem->is_file($file))
                unlink($file);
        }
    }
    /**
     * Test.
     * @since 3.1.6
     * @group logger
     */
    public function testInit()
    {
        // Prepare
        $this->setLogger();
        // Assert
        $this->assertInstanceOf(Log::class, $this->logger);
    }
    /**
     * Test.
     * @since 3.1.6
     * @group logger
     */
    public function testInstance()
    {
        // Assert
        $this->assertInstanceOf(Logger::class, Log::instance());
    }
    /**
     * Test.
     * @since 3.1.6
     * @group logger
     */
    public function testInfo()
    {
        // Prepare and execute
        Log::info('test_info');
        $contents = $this->getContents();
        // Assert
        $this->assertEquals(1, preg_match('/\[info\][\s\S]+test_info/', $contents));
    }
    /**
     * Test.
     * @since 3.1.6
     * @group logger
     */
    public function testDebug()
    {
        // Prepare and execute
        Log::debug('test_debug', 1);
        $contents = $this->getContents();
        // Assert
        $this->assertEquals(1, preg_match('/\[debug\][\s\S]+test_debug[\s\S]+1/', $contents));
    }
    /**
     * Test.
     * @since 3.1.6
     * @group logger
     */
    public function testError()
    {
        // Prepare and execute
        Log::error(new Exception('error'));
        $contents = $this->getContents();
        // Assert
        $this->assertEquals(1, preg_match('/\[error\][\s\S]+Exception[\s\S]+error[\s\S]+Stack trace\:/', $contents));
    }
    /**
     * Test.
     * @since 3.1.8
     * @group logger
     */
    public function testDebugOnlyMessage()
    {
        // Prepare and execute
        Log::debug('only_message');
        $contents = $this->getContents();
        // Assert
        $this->assertEquals(1, preg_match('/\[debug\][\s\S]+only_message/', $contents));
    }
    /**
     * Test.
     * @since 3.1.8
     * @group logger
     */
    public function testDebugDirectValue()
    {
        // Prepare and execute
        Log::debug(123);
        $contents = $this->getContents();
        // Assert
        $this->assertEquals(1, preg_match('/\[debug\][\s\S]+value[\s\S]+123/', $contents));
    }
}