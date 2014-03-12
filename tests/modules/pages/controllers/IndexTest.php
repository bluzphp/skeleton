<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Pages;

use Application\Tests\TestCase;

/**
 * @package Application\Tests\Pages
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class IndexTest extends TestCase
{
    /**
     * Dispatch controller only, w/out application
     * @expectedException \Bluz\Application\Exception\NotFoundException
     */
    public function testNotFoundPage()
    {
        $this->app->dispatch('pages','index', ['alias' => uniqid('random_name_')]);
    }
}
