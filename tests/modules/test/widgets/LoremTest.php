<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Test;

use Application\Tests\TestCase;

/**
 * @package Application\Tests\Test
 * @author   Anton Shevchuk
 * @created  19.05.2014 12:08
 */
class LoremTest extends TestCase
{
    /**
     * Check widget output
     */
    public function testLorem()
    {
        $this->expectOutputRegex('/^Lorem ipsum dolor sit amet/');
        $widget = $this->getApp()->widget('test', 'lorem');
        $widget();
    }
}
