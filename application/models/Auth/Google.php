<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 2/17/15
 * Time: 11:06 AM
 */

namespace Application\Auth;

use Bluz\Proxy\Config;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Router;
use Google\Client;

class Google extends AbstractAuth{

    /** @var  Client/Google */
    private $google;

    /** @var   */
    private $code;


    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        if (!$code) {
            $config = Config::getData('auth', 'google');
            $googleAuth = new Client($config);
            //todo::need to be wrapped in plugin
            $scheme = Request::getScheme() . '://';
            $host = Request::getHttpHost();
            $url = $config['redirect-uri'];
            $redirectUri = $scheme . $host . '/' . $url;
            $this->response->redirect($googleAuth->getAuthUrl($redirectUri));
        }

        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $profile
     * @param $user
     */
    public function registration($profile, $user)
    {
        $auth = new \Application\Auth\Entity\Auth();
        $auth->setForeignKey($profile['id']);
        $auth->setProvider(\Application\Auth\Entity\Auth::PROVIDER_GOOGLE);
        $auth->setToken($this->google->accessToken);
        if($this->google->refreshToken){
            $auth->setRefreshToken($this->google->refreshToken);
        }
        $auth->setTokenType('access');
        $auth->setUser($user);
        $auth->setTokenSecret(0);
        $this->authService->saveObject($auth);
        Messages::addNotice('Your account was linked to Google successfully !');
        $this->response->redirectTo('users', 'profile', ['id' => $user->getId()]);

    }


    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getOptions()
    {
        $options = Config::getData('auth', 'google');
        if (!$options || !isset($options['client_id'], $options['client_secret'])
            || empty($options['client_id']) || empty($options['client_secret'])
        ) {
            throw new \Exception('Google authorization is not configured');
        }
        return $options;
    }

    /**
     * @return void
     */
    public function redirectLogic()
    {
        $login_url = $this->google->getAuthUrl(Router::getFullUrl('google', 'redirect_uri'));
        $this->response->redirect($login_url);
    }

    /**
     * @param  Auth $auth
     * @throws \Bluz\Auth\AuthException
     * @throws \Exception
     */
    public function alreadyRegisteredLogic(Auth $auth)
    {
        $user = $auth->getUser();

        if ($user->getStatus() != User::STATUS_ACTIVE) {
            Messages::addError('User is not active');
        }

        $this->userService->login($user);
        $this->response->redirectTo('index', 'index');

    }

    /**
     * @return array
     */
    public function getProfile()
    {
        //extend access_token live or get new one
        $options = $this->getOptions();
        $this->google = new Client($options);
        $config = Config::getData('auth', 'google');
        //todo::need to be wrapped in plugin
        $scheme = Request::getScheme() . '://';
        $host = Request::getHttpHost();
        $url = $config['redirect-uri'];
        $redirectUri = $scheme . $host . '/' . $url;
        $this->google->getOauthAccessToken($this->code, $redirectUri); //getting temporary token
        $userGoogle = $this->google->getUserInfo();
        if ($userGoogle) {
            return $userGoogle;
        }
        /**
         * If user doesn't allow application yet, redirect him to fb page for this.
         * After this operation we will returned to this file.
         * Is user declined app, we get param 'error' => 'access_denied'
         */
        $this->redirectLogic();

    }


}