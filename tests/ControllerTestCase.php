<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests;

use Bluz\Router\Router;
use Application\Users;
use Application\Tests\Fixtures\Users\UserHasPermission;

/**
 * Controller TestCase
 *
 * @package Application\Tests
 *
 * @author   Anton Shevchuk
 * @created  06.05.2014 10:28
 */
class ControllerTestCase extends TestCase
{
    /**
     * Setup Guest
     *
     * @return void
     */
    protected function setupGuestIdentity()
    {
        $this->app->getAuth()->setIdentity(new Users\Row());
    }

    /**
     * Setup user with all privileges
     *
     * @return void
     */
    protected function setupSuperUserIdentity()
    {
        $this->app->getAuth()->setIdentity(new UserHasPermission());
    }

    /**
     * Assert Module
     *
     * @param string $module
     * @return void
     */
    protected function assertModule($module)
    {
        $this->assertEquals($module, $this->app->getModule());
    }

    /**
     * Assert Controller
     *
     * @param string $controller
     * @return void
     */
    protected function assertController($controller)
    {
        $this->assertEquals($controller, $this->app->getController());
    }

    /**
     * Assert redirect to another controller
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @param int $code
     * @return void
     */
    protected function assertRedirect($module, $controller, $params = array(), $code = 302)
    {
        $url = $this->app->getRouter()->url($module, $controller, $params);
        $exception = $this->app->getResponse()->getException();

        $this->assertInstanceOf('\Bluz\Application\Exception\RedirectException', $exception);
        $this->assertEquals($exception->getCode(), $code);
        $this->assertEquals($exception->getMessage(), $url);
    }

    /**
     * Assert reload page
     *
     * @return void
     */
    protected function assertReload()
    {
        $exception = $this->app->getResponse()->getException();

        $this->assertInstanceOf('\Bluz\Application\Exception\ReloadException', $exception);
    }

    /**
     * Assert forbidden
     *
     * @return void
     */
    protected function assertForbidden()
    {
        $exception = $this->app->getResponse()->getException();

        $this->assertInstanceOf('\Bluz\Application\Exception\ForbiddenException', $exception);
        $this->assertEquals(403, $this->app->getResponse()->getCode());
        $this->assertModule(Router::ERROR_MODULE);
        $this->assertController(Router::ERROR_CONTROLLER);
    }
}
 