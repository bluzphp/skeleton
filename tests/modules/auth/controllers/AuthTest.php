<?php

/**
 * PHP version 5
 *
 * @category Testing
 * @package  Application\Tests\Auth
 * @link https://github.com/bluzphp
 * @created  08.05.15
 */
namespace Application\Tests\Auth;

use Application\Auth\AuthProvider;
use Application\Tests\ControllerTestCase;
use Bluz\Application\Exception\RedirectException;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Db;
use Bluz\Proxy\Messages;

/**
 * Class AuthTest
 * @package Application\Tests\Auth
 */
class AuthTest extends ControllerTestCase
{

    protected function setUp()
    {
        parent::setUp();

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
        Db::delete('users')->where('id IN (?)', [2])->execute();
        Db::delete('auth')->where('userId IN (?)', [2])->execute();
        Messages::popAll();
    }

    public function testUserAlreadyLinkedTo()
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
        } catch (RedirectException $red) {
        } catch (\Exception $e) {
        }

        $message = Messages::pop();
        $this->assertEquals("You have already linked to Facebook", $message->text);

    }

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
        } catch (RedirectException $red) {
        } catch (\Exception $e) {
        }

        $message = Messages::pop();
        $this->assertEquals("First you need to be linked to Facebook", $message->text);

    }
}
