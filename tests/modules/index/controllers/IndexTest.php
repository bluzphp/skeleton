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
use Bluz\Router\Router;

/**
 * @package Application\Tests\Pages
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
        $this->assertModule(Router::DEFAULT_MODULE);
        $this->assertController(Router::DEFAULT_CONTROLLER);
        $this->assertQueryContentContains('h1', 'Congratulations!');
    }
}
