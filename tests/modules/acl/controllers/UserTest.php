<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Acl;

use Application\Tests\ControllerTestCase;
use Bluz\Proxy\Request;

/**
 * @package  Application\Tests\Acl
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class UserTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     *
     * @todo test functionality
     */
    public function testControllerPage()
    {
        $this->setupSuperUserIdentity();

        // user id is empty
        $this->dispatch('acl/user');
        $this->assertResponseCode(500);
    }

    /**
     * Dispatch module/controller
     *
     * @todo test functionality
     */
    public function testUserPage()
    {
        $this->setupSuperUserIdentity();

        // user id is admin
        $this->dispatch('acl/user', ['id' => 2]);

        $this->assertOk();
    }
}
