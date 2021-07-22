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

/**
 * @group    acl
 *
 * @package  Application\Tests\Acl
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class RolesTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     *
     * @todo test functionality
     */
    public function testControllerPage()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/acl/roles/');
        self::assertOk();
    }
}
