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
 * @group    system
 *
 * @package  Application\Tests\System
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class BookmarksTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testBookmarksPage()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/system/bookmarks');
        self::assertModule('system');
        self::assertController('bookmarks');
        self::assertOk();
        self::assertQueryContentContains('title', __('Bookmarklets'));
        self::assertQueryContentContains('h2', __('Bookmarklets for debug site'));
    }
}
