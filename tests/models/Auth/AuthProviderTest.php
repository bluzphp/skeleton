<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 07.05.15
 * Time: 11:40
 */

namespace Application\Tests\Auth;

use Application\Auth\AuthProvider;
use Application\Auth\Row;
use Application\Tests\ControllerTestCase;
use Bluz\Application\Exception\RedirectException;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Db;
use Bluz\Proxy\Messages;

class AuthProviderTest extends ControllerTestCase
{

    protected function setUp()
    {
        parent::setUp();

        Db::insert('users')->setArray(
            [
                'id' => 1,
                'login' => 'Donatello',
                'email' => 'donatello@turtles.org',
                'status' => 'pending'
            ]
        )->execute();

        Db::insert('users')->setArray(
            [
                'id' => 2,
                'login' => 'Bill',
                'email' => 'bill@turtles.org',
                'status' => 'active'
            ]
        )->execute();

        Db::insert('auth')->setArray(
            [
                'provider' => 'facebook',
                'userId' => 2,
                'foreignKey' => 112233
            ]
        )->execute();

        Auth::setIdentity(new \Application\Users\Row());
    }

    protected function tearDown()
    {
        Db::delete('users')->where('id IN (?)', [1, 2])->execute();
        Db::delete('auth')->where('userId IN (?)', [2])->execute();
        Messages::popAll();
    }

    public static function setUpBeforeClass()
    {
    }


    public static function tearDownAfterClass()
    {
    }


    /**
     * @expectedException \Exception
     */

    public function testProviderNotFound()
    {
        new AuthProvider('fake_data');
    }

    /**
     * @expectedException \Bluz\Auth\AuthException
     */
    public function testUserStatusNotActive()
    {

        $provider = new AuthProvider('Facebook');
        $authRow = new Row();
        $authRow->userId = 1;
        $provider->alreadyRegisteredLogic($authRow);
    }

    public function testUserStatusActive()
    {

        $provider = new AuthProvider('Facebook');
        $provider->setResponse($this->getApp());
        $authRow = new Row();
        $authRow->userId = 2;
        try {
            $provider->alreadyRegisteredLogic($authRow);
        } catch (RedirectException $e) {

        }
        $this->assertNotNull(Auth::getIdentity());

    }


    /**
     * @expectedException \Exception
     */
    public function testFailureHybridProvider()
    {

        $provider = new AuthProvider('olo');
        $this->assertInstanceOf('\Hybrid_Provider_Adapter', $provider->authenticate('olo'));
    }

    public function testOptions()
    {
        $provider = new AuthProvider('Facebook');
        $this->assertNotEmpty($provider->getOptions());

    }


    public function testAvailableProviders()
    {

        $provider = new AuthProvider('Facebook');
        $this->assertContains("Facebook", $provider->getAvailableProviders());
    }

  /*  public function testUserAlreadyLinkedTo()
    {

        $identity = new \Application\Users\Row();
        $identity->id = 2;

        $userProfile = new \Hybrid_User_Profile();
        $userProfile->identifier = 112233;

        $hybridAuthMock = $this->getMockBuilder('\Hybrid_Auth')
            ->setMethods(['authenticate'])
            ->disableOriginalConstructor()
            ->getMock();

        $authAdapterMock = $this->getMockBuilder('\Hybrid_Provider_Adapter')
            ->setMethods(['getUserProfile'])
            ->disableOriginalConstructor()
            ->getMock();

        $authAdapterMock->method('getUserProfile')
            ->willReturn($userProfile);

        $hybridAuthMock->method('authenticate')
            ->willReturn(new \Hybrid_Provider_Adapter);

        $this->assertInstanceOf('\Hybrid_Auth', $hybridAuthMock);

        $provider = new AuthProvider('Facebook');
        $provider->setResponse($this->getApp());
        $provider->setIdentity($identity);
        $provider->setHybridauth($hybridAuthMock);
        $provider->setAuthAdapter($authAdapterMock);
        try {
            $provider->authProcess();
        }
        catch (RedirectException $red) {}
        catch (\Exception $e) {}

        $message = Messages::pop();
        $this->assertEquals("You have already linked to Facebook", $message->text );

    }*/

    public function testUserNotLinkedTo()
    {
        $hybridAuthMock = $this->getMockBuilder('\Hybrid_Auth')
            ->setMethods(['authenticate'])
            ->disableOriginalConstructor()
            ->getMock();

        $authAdapterMock = $this->getMockBuilder('\Hybrid_Provider_Adapter')
            ->setMethods(['getUserProfile'])
            ->disableOriginalConstructor()
            ->getMock();

        $hybridAuthMock->method('authenticate')
            ->willReturn(new \Hybrid_Provider_Adapter);


        $this->assertInstanceOf('\Hybrid_Auth', $hybridAuthMock);

        $provider = new AuthProvider('Facebook');
        $provider->setResponse($this->getApp());
        $provider->setHybridauth($hybridAuthMock);
        $provider->setAuthAdapter($authAdapterMock);
        try {
            $provider->authProcess();
        }
        catch (RedirectException $red) {}
        catch (\Exception $e) {}

        $message = Messages::pop();
        $this->assertEquals("First you need to be linked to Facebook", $message->text );

    }


}