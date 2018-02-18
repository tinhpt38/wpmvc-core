<?php
/**
 * Tests global functions.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.0.16
 */
class FunctionsTest extends PHPUnit_Framework_TestCase
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
}