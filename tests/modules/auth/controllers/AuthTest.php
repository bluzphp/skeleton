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

    /**
     * @var \Hybrid_Auth
     */
    protected $hybridAuthMock;

    /**
     * @var \Hybrid_Provider_Adapter
     */
    protected  $authAdapterMock;

    protected function setUp()
    {
        parent::setUp();

        $this->hybridAuthMock = $this->getMockBuilder('\Hybrid_Auth')
            ->setMethods(['authenticate'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->authAdapterMock = $this->getMockBuilder('\Hybrid_Provider_Adapter')
            ->setMethods(['getUserProfile'])
            ->disableOriginalConstructor()
            ->getMock();

        Db::insert('users')->setArray(
            [
                'id' => 3,
                'login' => 'Bill',
                'email' => 'bill@turtles.org',
                'status' => 'active'
            ]
        )->execute();

        Db::insert('auth')->setArray(
            [
                'provider' => 'facebook',
                'userId' => 3,
                'foreignKey' => 112233,
                'token' => '',
                'tokenType'=>'access'
            ]
        )->execute();

        Auth::setIdentity(new \Application\Users\Row());
    }

    protected function tearDown()
    {
        Db::delete('users')->where('id IN (?)', [3])->execute();
        Db::delete('auth')->where('userId IN (?)', [3])->execute();
        Messages::popAll();
    }

    public function testUserAlreadyLinkedTo()
    {
        $identity = new \Application\Users\Row();
        $identity->id = 3;

        $userProfile = new \Hybrid_User_Profile();
        $userProfile->identifier = 112233;

        $this->authAdapterMock->method('getUserProfile')
            ->willReturn($userProfile);

        $this->hybridAuthMock->method('authenticate')
            ->willReturn(new \Hybrid_Provider_Adapter);

        $this->assertInstanceOf('\Hybrid_Auth', $this->hybridAuthMock);

        $provider = new AuthProvider('Facebook');
        $provider->setIdentity($identity);
        $provider->setHybridauth($this->hybridAuthMock);
        $provider->setAuthAdapter($this->authAdapterMock);
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
        $userProfile = new \Hybrid_User_Profile();
        $userProfile->identifier = null;

        $this->hybridAuthMock->method('authenticate')
            ->willReturn(new \Hybrid_Provider_Adapter);

        $this->authAdapterMock->method('getUserProfile')
            ->willReturn($userProfile);

        $this->assertInstanceOf('\Hybrid_Auth', $this->hybridAuthMock);

        $provider = new AuthProvider('Facebook');
        $provider->setHybridauth($this->hybridAuthMock);
        $provider->setAuthAdapter($this->authAdapterMock);
        try {
            $provider->authProcess();
        } catch (RedirectException $red) {
        } catch (\Exception $e) {
        }

        $message = Messages::pop();
        $this->assertEquals("First you need to be linked to Facebook", $message->text);

    }
}
