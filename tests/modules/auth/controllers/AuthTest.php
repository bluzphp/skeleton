<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 07.05.15
 * Time: 11:37
 */

namespace Application\Tests\Auth;

use Application\Auth\AuthProvider;
use Application\Tests\ControllerTestCase;

class AuthTest extends ControllerTestCase{

    /**
     * Test user with correct password
     */
    public function testProviderLink()
    {
        $provider = 'Facebook';
        $mock = $this->getMockBuilder('\Hybrid_Auth')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertInstanceOf('\Hybrid_Auth', $mock);


    }

}