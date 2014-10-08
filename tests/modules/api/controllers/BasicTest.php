<?php
/**
 * @author   Dmitriy Rassadkin
 * @created  29.09.14 10:37
 */

namespace Application\Tests\Api;

use Application\Tests\ControllerTestCase;

class IndexTest extends ControllerTestCase
{
    public function testMethodNotFound()
    {
        $response = $this->dispatchRouter('/api/not-exists');
        //resource not-exists not exists => not found
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testWrongMethod()
    {
        $response = $this->dispatchRouter('api/login', [], 'GET');
        //get is not allowed => not implemented
        $this->assertEquals(501, $response->getStatusCode());
    }

    public function testMissingParam()
    {
        $response = $this->dispatchRouter('api/login', ['login' => 'admin'], 'POST');
        //missed password => bad request
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testLoginWrongPassword()
    {
        $response = $this->dispatchRouter('api/login', ['login' => 'admin', 'password' => 'password'], 'POST');
        //wrong password => authorization failed
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testLoginSuccess()
    {
        $response = $this->dispatchRouter('api/login', ['login' => 'admin', 'password' => 'admin'], 'POST');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', $response->getBody()->getData());
    }
}
