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
class ExampleTest extends TestCase
{
    /**
     * testExample
     *
     * @return void
     */
    public function testExample()
    {
        $closure = $this->getApp()->api('test', 'example');
        $this->assertEquals(4, $closure(2));
    }
}
