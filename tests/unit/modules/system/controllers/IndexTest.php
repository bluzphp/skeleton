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
class IndexTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testBookmarksPage()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/system/');
        self::assertModule('system');
        self::assertController('index');
        self::assertOk();
    }
}
