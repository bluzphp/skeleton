<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Test;

use Application\Tests\ControllerTestCase;

/**
 * @package  Application\Tests\Test
 * @author   Anton Shevchuk
 * @created  26.05.14 12:36
 */
class DbTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testControllerPage()
    {
        $this->dispatch('/test/db/');
        $this->assertOk();
    }
}
