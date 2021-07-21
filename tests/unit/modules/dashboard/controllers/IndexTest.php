<?php

/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Dashboard;

use Application\Tests\ControllerTestCase;
use Bluz\Http\Exception\ForbiddenException;
use Bluz\Proxy\Response;

/**
 * @group    dashboard
 *
 * @package  Application\Tests\Dashboard
 * @author   Anton Shevchuk
 * @created  28.03.14 17:08
 */
class IndexTest extends ControllerTestCase
{
    /**
     * Dispatch controller w/out application:
     *  - as user
     *  - w/out permission
     *
     */
    public function testForbidden()
    {
        $this->expectException(ForbiddenException::class);

        self::setupGuestIdentity();
        self::getApp()->dispatch('dashboard', 'index');
    }

    /**
     * Dispatch controller w/out application as user w/out permission
     */
    public function testForbiddenByUri()
    {
        self::setupGuestIdentity();

        $this->dispatch('dashboard/index');

        self::assertForbidden();
    }

    /**
     * Dispatch controller
     *  - as user
     *  - w/out permission
     */
    public function testError()
    {
        self::setupGuestIdentity();

        $this->dispatch('dashboard/index');

        self::assertEquals(403, Response::getStatusCode());
    }

    /**
     * Dispatch controller
     *  - as user
     *  - with permission
     */
    public function testIndex()
    {
        self::setupSuperUserIdentity();
        $this->dispatch('dashboard/index');

        self::assertEquals(200, Response::getStatusCode());
    }
}
