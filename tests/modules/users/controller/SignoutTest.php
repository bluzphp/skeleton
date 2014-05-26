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

        $this->getApp()->getAuth()->setIdentity(new Row());
    }

    /**
     * Test sign out user
     */
    public function testSignOut()
    {
        $this->assertNotNull($this->getApp()->getAuth()->getIdentity());

        $this->dispatchUri('users/signout');

        $this->assertModule('users');
        $this->assertController('signout');
        $this->assertNull($this->getApp()->getAuth()->getIdentity());
    }
}
