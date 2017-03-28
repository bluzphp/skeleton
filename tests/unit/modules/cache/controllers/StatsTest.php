<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Cache;

use Application\Tests\ControllerTestCase;

/**
 * @group    cache
 *
 * @package  Application\Tests\Cache
 * @author   Anton Shevchuk
 * @created  15.05.14 12:24
 */
class StatsTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     *
     * @todo test functionality
     */
    public function testControllerPage()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/cache/stats/');
        self::assertRedirect('cache', 'index');
        self::assertNoticeMessage();
    }
}
