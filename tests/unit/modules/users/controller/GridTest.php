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
 * @group    users
 * @group    grid
 *
 * @package  Application\Tests\Users
 * @author   Anton Shevchuk
 * @created  27.05.2014 14:26
 */
class GridTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testBookmarksPage()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/users/grid/');
        self::assertModule('users');
        self::assertController('grid');
        self::assertOk();
        self::assertQuery('table.grid');
    }
}
