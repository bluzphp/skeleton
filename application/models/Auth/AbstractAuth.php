<?php

namespace Application\Auth;

use Bluz\Proxy\Config;
use Bluz\Proxy\Messages;
use Application\Auth;
use Application\Users;

abstract class AbstractAuth implements AuthInterface
{
    /** @var  \Bluz\Http\Response */
    protected $response;

    /** @var \Application\Users\Row $identity */
    protected $identity;

    /** @var \Hybrid_Auth $hybridauth */
    protected $hybridauth;

    /** @var \Hybrid_Provider_Adapter $authAdapter*/
    protected $authAdapter;


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
     * @param \\Application\Users\Row $user $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return \Application\Users\Row $user
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param \Hybrid_User_Profile $data
     * @param \Application\Users\Row $user $user
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
        $providerName = $this->getProviderName();
        $profile = $this->getProfile(); //?

        /**
         * @var Auth\Table $authTable
         */
        $authTable = Auth\Table::getInstance();
        $auth = $authTable->getAuthRow(strtolower($providerName), $profile->identifier);

        if ($this->identity) {
            if ($auth) {
                Messages::addNotice(sprintf('You have already linked to %s', $providerName));
                $this->response->redirectTo('users', 'profile', ['id' => $this->identity->id]);
            } else {
                $user = Users\Table::findRow($this->identity->id);
                $this->registration($profile, $user);
            }
        }

        if ($auth) {
            $this->alreadyRegisteredLogic($auth);
        } else {
            Messages::addError('You need to sign in first');
            $this->response->redirectTo('users', 'signin');
        }
    }

    /**
     * @return string
     */
    private function getProviderName(){

        $elements = explode('\\', get_class($this));
        return end($elements);
    }

    /**
     * @return array
     * @throws \Application\Exception
     */
    public function getOptions()
    {
       return Config::getData('hybridauth');
    }

    /**
     * @param Auth $auth
     * @return mixed
     */
    public function alreadyRegisteredLogic($auth)
    {
        // TODO: Implement alreadyRegisteredLogic() method.
    }

    /**
     * @return \Hybrid_User_Profile
     */
    public function getProfile()
    {
        $this->hybridauth = new \Hybrid_Auth($this->getOptions());

        /** @var \Hybrid_Provider_Adapter $authProvider */
        $this->authAdapter= $this->hybridauth->authenticate($this->getProviderName());

        return $this->authAdapter->getUserProfile();
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