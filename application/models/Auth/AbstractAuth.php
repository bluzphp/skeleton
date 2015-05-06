<?php

namespace Application\Auth;

use Bluz\Proxy\Messages;
use Application\Auth;
use Application\Users;
use Bluz\Proxy\Request;
use Bluz\Proxy\Session;

class AbstractAuth implements AuthInterface
{

    /** @var  \Bluz\Http\Response */
    protected $response;

    /** @var \Application\Users\Entity\User $identity */
    protected $identity;

    private $hybridauth;

    /**
     * @return \Hybrid_Auth
     */
    public function getHybridauth()
    {
        return $this->hybridauth = new \Hybrid_Auth($this->getOptions());
    }

    /**
     * @param mixed $hybridauth
     */
    public function setHybridauth($hybridauth)
    {
        $this->hybridauth = $hybridauth;
    }

    /**
     * @param \Bluz\Http\Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return \Bluz\Http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Application\AbstractService $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return \Application\AbstractService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param \Application\Users\Entity\User $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return \Application\Users\Entity\User
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param \Application\Auth\AuthService $authService
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return \Application\Auth\AuthService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @param \Application\Users\UserService $userService
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return \Application\Users\UserService
     */
    public function getUserService()
    {
        return $this->userService;
    }


    /**
     * @param array $data
     * @param \Application\Users\Entity\User $user
     * @return void
     */
    public function registration($data, $user)
    {
        // TODO: Implement registration() method.
    }

    /**
     * @return void
     */
    public function authProcess()
    {
        $elements = explode('\\', get_class($this));
        $providerName = end($elements);
        $profile = $this->getProfile();

        if (!$profile) {
            /**
             * If user doesn't allow application yet, redirect him to fb page for this.
             * After this operation we will returned to this file.
             * Is user declined app, we get param 'error' => 'access_denied'
             */

            // if user declined
            if ('access_denied' == Request::getParam('error', null)) {
                $this->response->redirectTo('users', 'signin');
            }
        }

        /* if ( \Bluz\Proxy\Auth::getIdentity()) {
             $this->response->redirectTo('index', 'index');
         }*/

        /**
         * @var Auth\Table $authTable
         */
        $authTable = Auth\Table::getInstance();
        $auth = $authTable->getAuthRow(strtolower($providerName), $profile['id']);

        if ($auth) {
            // if user has been registered
            $user = Users\Table::findRow($auth->userId);

            $user->login();

            if ($user->status != Users\Table::STATUS_ACTIVE) {
                Messages::addError('User is not active');
            }

            $this->response->redirectTo('index', 'index');
        } else {
            // sign up user

            // continue with registration
            /**
             * Write facebook response to session
             */
            Session::set('facebook', $profile);
            Messages::addNotice('To finish your registration fill the form');
            $this->redirectTo('users', 'signup');


        }
        /*  if ($this->identity) {
              if ($auth) {
                  Messages::addNotice(sprintf('You have already linked to %s', $providerName));
                  $this->response->redirectTo('users', 'profile', ['id' => $this->identity->getId()]);
              } else {
                  $user = $this->userService->readOne($this->identity->getId());
                  $this->registration($profile, $user);
              }
          }

          if ($auth) {
              $this->alreadyRegisteredLogic($auth); //need to be not a proxy object
          } else {
              Messages::addError('You need to sign up via Ldap first');
              $this->response->redirectTo('users', 'signin');
          }*/

    }

    /**
     * @return array
     * @throws \Application\Exception
     */
    private function getOptions()
    {

    }

    /**
     * @return void
     */
    public function redirectLogic()
    {
        // TODO: Implement redirectLogic() method.
    }

    /**
     * @param Auth $auth
     * @return mixed
     */
    public function alreadyRegisteredLogic(Auth $auth)
    {
        // TODO: Implement alreadyRegisteredLogic() method.
    }

    /**
     * @return array
     */
    public function getProfile()
    {
        // TODO: Implement getProfile() method.
    }

    public function setProvider($provider)
    {
        // TODO: Implement setProvider() method.
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        // TODO: Implement getProvider() method.
    }
}