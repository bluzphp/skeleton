<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Pages;

use Application\Tests\ControllerTestCase;

/**
 * @package Application\Tests\Pages
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class IndexTest extends ControllerTestCase
{
    /**
     * Dispatch controller only, w/out application
     * @expectedException \Bluz\Application\Exception\NotFoundException
     */
    public function testNotFoundPage()
    {
        $this->getApp()->dispatch('pages', 'index', ['alias' => uniqid('random_name_')]);
    }

    /**
     * Dispatch "About" page
     */
    public function testIndexPage()
    {
        $this->dispatch('/about.html');
        $this->assertModule('pages');
        $this->assertController('index');
        $this->assertQueryContentContains('h2.page-header', 'About Bluz Framework');
    }
}
