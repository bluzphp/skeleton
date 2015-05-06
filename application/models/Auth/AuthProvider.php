<?php

namespace Application\Auth;

use Bluz\Proxy\Config;
use Bluz\Proxy\Messages;
use Application\Auth;
use Application\Users;

class AuthProvider
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
     * @var string
     */
    protected $providerName;

    public function __construct($providerName){

        $this->providerName = $providerName;
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
        $twitterRow = new Auth\Row();
        $twitterRow->userId = $user->id;
        $twitterRow->provider = strtolower($this->providerName);

        $twitterRow->foreignKey = $data->identifier;
        $twitterRow->token = $this->authAdapter->getAccessToken()['access_token'];
        $twitterRow->tokenSecret = ($this->authAdapter->getAccessToken()['access_token_secret'])?
            $this->authAdapter->getAccessToken()['access_token_secret']: '' ;
        $twitterRow->tokenType = Auth\Table::TYPE_ACCESS;
        $twitterRow->save();

        Messages::addNotice(sprintf('Your account was linked to %s successfully !', ucfirst($this->providerName)));
        $this->response->redirectTo('users', 'profile', ['id' => $user->id]);
    }


    /**
     * @return void
     */
    public function authProcess()
    {
        $profile = $this->getProfile();

        /**
         * @var Auth\Table $authTable
         */
        $authTable = Auth\Table::getInstance();
        $auth = $authTable->getAuthRow(strtolower($this->providerName), $profile->identifier);

        if ($this->identity) {
            if ($auth) {
                Messages::addNotice(sprintf('You have already linked to %s', ucfirst($this->providerName)));
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
     * @return array
     * @throws \Application\Exception
     */
    public function getOptions()
    {
       return Config::getData('hybridauth');
    }

    /**
     * @param $auth
     * @return mixed
     */
    public function alreadyRegisteredLogic($auth)
    {
        $user = Users\Table::findRow($auth->userId);

        if ($user->status != Users\Table::STATUS_ACTIVE) {
            Messages::addError('User is not active');
        }

        $user->login();
        $this->response->redirectTo('index', 'index');
    }

    /**
     * @return \Hybrid_User_Profile
     */
    public function getProfile()
    {
        $this->hybridauth = new \Hybrid_Auth($this->getOptions());

        /** @var \Hybrid_Provider_Adapter $authProvider */
        $this->authAdapter= $this->hybridauth->authenticate(ucfirst($this->providerName));

        return $this->authAdapter->getUserProfile();
    }

}