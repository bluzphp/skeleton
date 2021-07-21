<?php

/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Pages;

use Application\Tests\ControllerTestCase;

/**
 * @group    pages
 * @group    grid
 *
 * @package  Application\Tests\Pages
 * @author   Anton Shevchuk
 * @created  27.05.2014 14:26
 */
class GridTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testGridPage()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/pages/grid/');

        self::assertModule('pages');
        self::assertController('grid');
        self::assertOk();
        self::assertQuery('table.grid');
    }
}
