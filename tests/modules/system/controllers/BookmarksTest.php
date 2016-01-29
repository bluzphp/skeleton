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
use Bluz\Proxy\Response;

/**
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
        $this->setupSuperUserIdentity();

        $this->dispatch('/system/bookmarks');
        $this->assertModule('system');
        $this->assertController('bookmarks');
        $this->assertOk();
        $this->assertQueryContentContains('title', __('Bookmarklets'));
        $this->assertQueryContentContains('h2', __('Bookmarklets for debug site'));
    }
}
