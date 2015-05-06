<?php

namespace Application\Auth;

use Application\Exception;
use Application\Auth\Table as AuthTable;
use Bluz\Proxy\Config;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

class Facebook extends AbstractAuth
{

    /** @var $facebook \Application\Facebook\Facebook */
    private $facebook;

    /**
     * @param array $profile
     * @param User $user
     */
    public function registration($profile, $user)
    {
        $auth = new Auth();
        $auth->setForeignKey($profile['id']);
        $auth->setProvider(Auth::PROVIDER_FACEBOOK);
        $auth->setToken($this->facebook->getAccessToken());
        $auth->setTokenType(AuthTable::TYPE_ACCESS);
        $auth->setUser($user);
        $auth->setTokenSecret(0);
        $this->authService->saveObject($auth);
        Messages::addNotice('Your account was linked to Facebook successfully !');
        $this->response->redirectTo('users', 'profile', ['id' => $user->getId()]);

    }

    /**
     * @return array
     */
    public function getProfile()
    {
        /** @var \Hybrid_Auth $hybridauth */
        $hybridauth = $this->getHybridauth();

        $facebook = $hybridauth->authenticate( "Facebook" );

        return $facebook->getUserProfile();
    }

    /**
     * void
     */
    public function redirectLogic()
    {
        $facebookConf = Config::getData('auth', 'facebook');
        //todo::need to be wrapped in plugin
        $scheme = Request::getScheme() . '://';
        $host = Request::getHttpHost();
        $url = $facebookConf['redirect-uri'];

        // if user declined
        if ('access_denied' == Request::getParam('error', null)) {
            $this->response->redirectTo('users', 'profile', ['id' => $this->identity->getId()]);
        }
        $login_url = $this->facebook->getLoginUrl(array(
            'scope' => 'email',
            'redirect_uri' => $scheme . $host . '/' . $url));
        $this->response->redirect($login_url);
    }

    /**
     * @param Auth $auth
     * @throws \Bluz\Auth\AuthException
     * @throws \Exception
     */
    public function alreadyRegisteredLogic(Auth $auth)
    {
        $auth->setToken($this->facebook->getAccessToken());
        $this->authService->updateObject($auth);
        $user = $auth->getUser();

        if ($user->getStatus() != User::STATUS_ACTIVE) {
            Messages::addError('User is not active');
        }

        $this->userService->login($user);
        $this->response->redirectTo('index', 'index');
    }

}