<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Media;

use Application\Tests\ControllerTestCase;

/**
 * @package  Application\Tests\Media
 * @author   Anton Shevchuk
 * @created  27.05.2014 14:26
 */
class GridTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testControllerPage()
    {
        $this->setupSuperUserIdentity();

        $this->dispatch('/media/grid/');
        $this->assertModule('media');
        $this->assertController('grid');
        $this->assertOk();
        $this->assertQuery('div[data-spy="grid"]');
    }
}
