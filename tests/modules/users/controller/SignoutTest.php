<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Users;

use Application\Tests\TestCase;
use Application\Users\Row;

/**
 * @package Application\Tests\Users
 * @author   Anton Shevchuk
 * @created  04.08.11 19:40
 */
class SignoutTest extends TestCase
{
    /**
     * setUp
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->app->getAuth()->setIdentity(new Row());
    }

    /**
     * Test sign out user
     */
    public function testSignOut()
    {
        $this->assertNotNull($this->app->getAuth()->getIdentity());

        $this->dispatchUri('users/signout');

        $this->assertNull($this->app->getAuth()->getIdentity());
    }
}
