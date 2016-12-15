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
use Application\Users\Row;
use Bluz\Proxy\Auth;

/**
 * @package Application\Tests\Users
 * @author   Anton Shevchuk
 * @created  04.08.11 19:40
 */
class SignoutTest extends ControllerTestCase
{
    /**
     * setUp
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        Auth::setIdentity(new Row());
    }

    /**
     * Test sign out user
     */
    public function testSignOut()
    {
        self::assertNotNull(Auth::getIdentity());

        $this->dispatch('users/signout');

        self::assertModule('users');
        self::assertController('signout');
        self::assertNull(Auth::getIdentity());
    }
}
