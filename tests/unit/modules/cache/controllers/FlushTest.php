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
use Bluz\Http\RequestMethod;

/**
 * @package  Application\Tests\Cache
 * @author   Anton Shevchuk
 * @created  15.05.14 12:24
 */
class FlushTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     *
     * @todo test functionality
     */
    public function testControllerPage()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/cache/flush/', [], RequestMethod::GET, true);
        self::assertOk();
        self::assertNoticeMessage();
    }
}