<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 06.05.15
 * Time: 11:02
 */

namespace Application\Auth;

use Application\Auth;
use Application\Users;
use Bluz\Proxy\Messages;

class Twitter extends AbstractAuth
{

    /**
     * @param \Hybrid_User_Profile $profile
     * @param  \Application\Users\Row $user
     */
    public function registration($profile, $user)
    {
        $twitterRow = new Auth\Row();
        $twitterRow->userId = $user->id;
        $twitterRow->provider = Auth\Table::PROVIDER_TWITTER;

        $twitterRow->foreignKey = $profile->identifier;
        $twitterRow->token = $this->authAdapter->getAccessToken()['access_token'];
        $twitterRow->tokenSecret = $this->authAdapter->getAccessToken()['access_token_secret'];
        $twitterRow->tokenType = Auth\Table::TYPE_ACCESS;
        $twitterRow->save();

        Messages::addNotice('Your account was linked to Twitter successfully !');
        $this->response->redirectTo('users', 'profile', ['id' => $user->id]);
    }

    /**
     * @param \Application\Auth\Row $auth
     * @return mixed
     */
    public function alreadyRegisteredLogic($auth){

        $user = Users\Table::findRow($auth->userId);

        if ($user->status != Users\Table::STATUS_ACTIVE) {
            Messages::addError('User is not active');
        }

        $user->login();
        $this->response->redirectTo('index', 'index');
    }

}
