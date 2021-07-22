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
use Bluz\Http\Exception\NotFoundException;

/**
 * @group    pages
 *
 * @package  Application\Tests\Pages
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class IndexTest extends ControllerTestCase
{
    /**
     * Dispatch controller only, w/out application
     */
    public function testNotFoundPage()
    {
        $this->expectException(NotFoundException::class);

        self::getApp()->dispatch('pages', 'index', ['alias' => uniqid('random_name_', true)]);
    }

    /**
     * Dispatch "About" page
     */
    public function testIndexPage()
    {
        $this->dispatch('/about.html');
        self::assertModule('pages');
        self::assertController('index');
        self::assertQueryContentContains('h1', 'About');
    }
}
