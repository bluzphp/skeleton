<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Index;

use Application\Tests\ControllerTestCase;
use Bluz\Proxy\Router;

/**
 * @group    index
 *
 * @package  Application\Tests\Pages
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class IndexTest extends ControllerTestCase
{
    /**
     * Dispatch default module/controller
     */
    public function testIndexPage()
    {
        $this->dispatch('/');
        self::assertModule(Router::getDefaultModule());
        self::assertController(Router::getDefaultController());
        self::assertQueryContentContains('h1', 'Congratulations!');
    }
}
