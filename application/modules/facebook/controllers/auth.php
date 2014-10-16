<?php
/**
 * Facebook Auth controller
 *
 * @author  Sergey Volkov <volkov.sergey@nixsolutions.com>
 * @created 22.05.2013 13:12
 */

/**
 * @namespace
 */
namespace Application;

use Application\Auth;
use Application\Facebook;
use Application\Users;
use Bluz\Proxy\Config;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Session;

return
/**
 * @throws Exception
 * @return void
 */
function () {
    /**
     * @var Bootstrap $this
     */
    $options = Config::getData('auth', 'facebook');
    if (!$options || !isset($options['appId'], $options['secret'])
        || empty($options['appId']) || empty($options['secret'])) {
        throw new Exception('Facebook authorization is not configured');
    }

    // redirect sign in user to index page and init session
    if ($this->user()) {
        $this->redirectTo('index', 'index');
    }

    $facebook = new Facebook\Facebook(
        array(
            'appId'  => $options['appId'],
            'secret' => $options['secret'],
        )
    );
    /**
     * Should be return id of user, if he allows application.
     * In false returned 0.
     */

    $facebook->destroySession();
    $user = $facebook->getUser();

    if ($user) {
        // Proceed knowing you have a logged in user who's authenticated.
        $profile = $facebook->api('/me');

        /**
         * @var Auth\Table $authTable
         */
        $authTable = Auth\Table::getInstance();
        $row = $authTable->getAuthRow(Auth\Table::PROVIDER_FACEBOOK, $profile['id']);

        if ($row) {
            // if user has been registered
            $user = Users\Table::findRow($row->userId);

            if ($user->status != Users\Table::STATUS_ACTIVE) {
                Messages::addError('User is not active');
                $this->redirectTo('index', 'index');
            }

            $user->login();
        } else {
            // sign up user

            if (!$user = $this->user()) {
                /**
                 * Write facebook response to session
                 */
                Session::set('facebook', $profile);
                Messages::addNotice('To finish your registration fill the form');
                $this->redirectTo('users', 'signup');
            } else {
                $row = new Auth\Row();
                $row->userId = $user->id;
                $row->provider = Auth\Table::PROVIDER_FACEBOOK;
                $row->foreignKey = $profile['id'];
                $row->token = $facebook->getAccessToken();
                $row->tokenSecret = 0;
                $row->tokenType = Auth\Table::TYPE_ACCESS;
                $row->save();
            }
        }
    } else {
        /**
         * If user doesn't allow application yet, redirect him to fb page for this.
         * After this operation we will returned to this file.
         * Is user declined app, we get param 'error' => 'access_denied'
         */

        // if user declined
        if ('access_denied' == Request::getParam('error', null)) {
            $this->redirectTo('users', 'signin');
        }

        $login_url = $facebook->getLoginUrl(array('scope' => 'email'));
        $this->redirect($login_url);
    }

    $this->redirectTo('index', 'index');
};
