<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Api;

use Application\Tests\ControllerTestCase;
use Bluz\Proxy\Response;

/**
 * @author   Dmitriy Rassadkin
 * @created  29.09.14 10:37
 */
class ApiTest extends ControllerTestCase
{
    public function testMethodNotFound()
    {
        $this->dispatch('api/not-exists');
        // resource not-exists not exists => not found
        $this->assertResponseCode(404);
    }

    public function testWrongMethod()
    {
        $this->dispatch('api/login', [], 'GET');
        // get is not allowed => not implemented
        $this->assertResponseCode(501);
    }

    public function testMissingParam()
    {
        $this->dispatch('api/login', ['login' => 'admin'], 'POST');
        // missed password => bad request
        $this->assertResponseCode(400);
    }

    public function testLoginWrongPassword()
    {
        $this->dispatch('api/login', ['login' => 'admin', 'password' => 'password'], 'POST');
        // wrong password => authorization failed
        $this->assertResponseCode(401);
    }

    public function testLoginSuccess()
    {
        $this->dispatch('api/login', ['login' => 'admin', 'password' => 'admin'], 'POST');

        $this->assertOk();
        $this->assertArrayHasKey('token', Response::getBody()->getData()->toArray());
    }
}
