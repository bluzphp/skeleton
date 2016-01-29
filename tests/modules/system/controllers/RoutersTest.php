<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\System;

use Application\Tests\ControllerTestCase;

/**
 * @package  Application\Tests\System
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class RoutersTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testBookmarksPage()
    {
        $this->setupSuperUserIdentity();

        $this->dispatch('/system/routers/');
        $this->assertModule('system');
        $this->assertController('routers');
        $this->assertOk();
        $this->assertQueryCount('table.table', 1);
    }
}
