<?php

use WPMVC\Response;
use PHPUnit\Framework\TestCase;

/**
 * Tests response class.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 3.0.0
 */
class ResponseTest extends TestCase
{
    /**
     * Test response.
     * @group response
     */
    function testDefaultFalse()
    {
        // Prepare
        $response = new Response;

        // Assert
        $this->assertFalse($response->success);
        $this->assertTrue($response->passes);
    }
    /**
     * Test response.
     * @group response
     */
    function testDefaultTrue()
    {
        // Prepare
        $response = new Response(true);

        // Assert
        $this->assertTrue($response->success);
        $this->assertTrue($response->passes);
    }
    /**
     * Test response.
     * @group response
     */
    function testError()
    {
        // Prepare
        $response = new Response;

        // Execute
        $response->error('field', 'Error');

        // Assert
        $this->assertTrue($response->fails);
        $this->assertFalse($response->passes);
        $this->assertArrayHasKey('field', $response->errors);
        $this->assertInternalType('array', $response->errors['field']);
        $this->assertEquals('Error', $response->errors['field'][0]);
    }
    /**
     * Test response.
     * @group response
     */
    function testCastingFail()
    {
        // Prepare
        $response = new Response;
        $response->message = 'An error';
        $response->error('field', 'Error');

        // Execute
        $r = $response->to_array();

        // Assert
        $this->assertInternalType('array', $r);
        $this->assertArrayHasKey('error', $r);
        $this->assertArrayHasKey('errors', $r);
        $this->assertArrayHasKey('status', $r);
        $this->assertArrayHasKey('message', $r);
        $this->assertTrue($r['error']);
        $this->assertArrayHasKey('field', $r['errors']);
        $this->assertEquals($response->message, $r['message']);
        $this->assertEquals(500, $r['status']);
    }
    /**
     * Test response.
     * @group response
     */
    function testCastingSuccess()
    {
        // Prepare
        $response = new Response;
        $response->message = 'An error';
        $response->success = true;
        // Execute
        $r = $response->to_array();
        // Assert
        $this->assertInternalType('array', $r);
        $this->assertArrayHasKey('error', $r);
        $this->assertArrayHasKey('status', $r);
        $this->assertArrayHasKey('message', $r);
        $this->assertFalse($r['error']);
        $this->assertEquals($response->message, $r['message']);
        $this->assertEquals(200, $r['status']);
    }
}