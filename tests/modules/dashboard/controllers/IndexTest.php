<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Dashboard;

use Application\Tests\Fixtures\Users\UserHasPermission;
use Application\Tests\TestCase;
use Application\Users;

/**
 * @package Application\Tests\Dashboard
 * @author   Anton Shevchuk
 * @created  28.03.14 17:08
 */
class IndexTest extends TestCase
{
    /**
     * Dispatch controller w/out application:
     *  - as guest
     *  - w/out permission
     * @expectedException \Bluz\Application\Exception\RedirectException
     */
    public function testRedirect()
    {
        $this->app->dispatch('dashboard', 'index');
    }

    /**
     * Dispatch controller w/out application:
     *  - as user
     *  - w/out permission
     * @expectedException \Bluz\Application\Exception\ForbiddenException
     */
    public function testForbidden()
    {
        $this->app->getAuth()->setIdentity(new Users\Row());
        $this->app->dispatch('dashboard', 'index');
    }

    /**
     * Dispatch controller
     *  - as user
     *  - w/out permission
     */
    public function testError()
    {
        $this->app->getAuth()->setIdentity(new Users\Row());
        $result = $this->dispatchUri('dashboard/index');

        $this->assertEquals(403, $result->getCode());
    }

    /**
     * Dispatch controller
     *  - as user
     *  - with permission
     */
    public function testIndex()
    {
        $this->app->getAuth()->setIdentity(new UserHasPermission());
        $result = $this->dispatchUri('dashboard/index');

        $this->assertEquals(200, $result->getCode());
    }
}
