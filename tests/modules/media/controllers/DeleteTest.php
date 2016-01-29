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
class DeleteTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testDelete()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Dispatch module/controller
     */
    public function testDeleteError()
    {
        $this->dispatch('/media/delete/');
        $this->assertModule('error');
        $this->assertController('index');
    }
}
