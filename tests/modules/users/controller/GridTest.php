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
        $this->setupSuperUserIdentity();

        $this->dispatch('/users/grid/');
        $this->assertModule('users');
        $this->assertController('grid');
        $this->assertOk();
        $this->assertQuery('table.grid');
    }
}
