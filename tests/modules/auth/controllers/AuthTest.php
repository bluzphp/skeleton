<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 07.05.15
 * Time: 11:37
 */

namespace Application\Tests\Auth;

use Application\Tests\ControllerTestCase;

class AuthTest extends ControllerTestCase{

    public function testPushAndPop()
    {
        $stack = array();
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }

}