<?php

namespace Application\Tests;

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

        $this->assertNull($this->app->getAuth()->getIdentity());
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

        $this->assertNotNull($this->app->getAuth()->getIdentity());
    }
}
