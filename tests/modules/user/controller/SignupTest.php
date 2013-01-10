<?php

namespace Application;

class SignupTest extends TestCase
{

    /*
     * Test user with wrong password
     * */
    public function testSigninWithWrongPassword()
    {
        $request = $this->app->getRequest();
        $request->setMethod('POST');
        $request->setRequestUri('/users/signin');
        $request->setParams(['login' => 'admin', 'password' => 'admin123']);
        $this->dispatchRequest($request);
        $this->dispatchUri('/system');
        $this->assertEquals($this->app->getLayout()->title(), '403 Forbidden');
    }

    /*
     * Test user with correct password
     * */
    public function testSigninWithCorrectPassword()
    {
        $request = $this->app->getRequest();
        $request->setMethod('POST');
        $request->setRequestUri('/users/signin');
        $request->setParams(['login' => 'admin', 'password' => 'admin']);
        $this->dispatchRequest($request);
        $this->dispatchUri('/system/index');
        $this->assertNotEquals($this->app->getLayout()->title(), '403 Forbidden');
    }
}
