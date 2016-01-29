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
        $this->setupSuperUserIdentity();

        $this->dispatch('/pages/grid/');
        $this->assertModule('pages');
        $this->assertController('grid');
        $this->assertOk();
        $this->assertQuery('table.grid');
    }
}
