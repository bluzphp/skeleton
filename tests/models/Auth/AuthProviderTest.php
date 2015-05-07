<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 07.05.15
 * Time: 11:40
 */

namespace Application\Tests\Auth;

use Application\Auth\AuthProvider;

class AuthProviderTest extends \PHPUnit_Framework_TestCase{

    /**
     * @expectedException \Exception
     */
    public function testProviderNotFound()
    {
       $provider = new AuthProvider('test');
    }

   public function testHybridProvider(){

        $provider = new AuthProvider('Facebook');
        $this->assertInstanceOf('\Hybrid_Provider_Adapter', $provider->authenticate('Facebook'));
    }

    /**
     * @expectedException \Exception
     */
    public function testFailureHybridProvider(){

        $provider = new AuthProvider('olo');
        $this->assertInstanceOf('\Hybrid_Provider_Adapter', $provider->authenticate('olo'));
    }

    public function testOptions(){
        $provider = new AuthProvider('Facebook');
        $this->assertNotEmpty($provider->getOptions());

    }


    public function testAvailableProviders() {

        $provider = new AuthProvider('Facebook');
        $this->assertContains("Facebook", $provider->getAvailableProviders());
    }

}