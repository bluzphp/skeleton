<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Users;

use Application\Tests\ControllerTestCase;

/**
 * @package Application\Tests\Users
 * @author   Anton Shevchuk
 * @created  04.08.11 19:40
 */
class SigninTest extends ControllerTestCase
{
    /**
     * Test user with wrong password
     */
    public function testSigninWithWrongPassword()
    {
        $this->dispatchUri(
            'users/signin',
            ['login' => 'admin', 'password' => 'admin123'],
            'POST'
        );

        $this->assertModule('users');
        $this->assertController('signin');
        $this->assertNull($this->getApp()->getAuth()->getIdentity());
    }

    /**
     * Test user with correct password
     */
    public function testSigninWithCorrectPassword()
    {
        $this->dispatchUri(
            'users/signin',
            ['login' => 'admin', 'password' => 'admin'],
            'POST'
        );

        $this->assertModule('users');
        $this->assertController('signin');
        $this->assertNotNull($this->getApp()->getAuth()->getIdentity());
    }
}
