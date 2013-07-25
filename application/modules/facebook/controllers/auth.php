<?php
/**
 * Facebook Auth controller
 *
 * @author  Sergey Volkov <volkov.sergey@nixsolutions.com>
 * @created 22.05.2013 13:12
 */
namespace Application;

use Bluz;
use Application\Facebook;
use Application\Users;

return
/**
 * @return \closure
 */
function () {
    /**
     * @var $this \Bluz\Application
     */

    $options = $this->getConfigData('auth', 'facebook');
    if (!$options || !isset($options['appId']) || empty($options['secret'])
    || !isset($options['appId']) || empty($options['secret'])) {
        throw new Exception('Facebook authorization is not configured');
    }

    // redirect signin user to index page and init session
    if ($this->getAuth()->getIdentity()) {
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
        $user_profile = $facebook->api('/me');

        /**
         * @var Auth\Table $authTable
         */
        $authTable = Auth\Table::getInstance();
        $row = $authTable->getAuthRow(Auth\Row::PROVIDER_FACEBOOK, $user_profile['id']);

        if ($row) {
            // if user has been registered
            $user = Users\Table::findRow($row->userId);

            if ($user->status != Users\Row::STATUS_ACTIVE) {
                $this->getMessages()->addError('User is not active');
                $this->redirectTo('index', 'index');
            }

            $user->login();
        } else {
            // signup user
            if (!$user = $this->getAuth()->getIdentity()) {
                $user = new Users\Row();
                // if username doesn't exist, concat first and last name for site's login
                $login = (isset($user_profile['username']))
                    ? $user_profile['username']
                    : $user_profile['first_name'] . $user_profile['last_name'];
                $user->login = $login;
                $user->status = Users\Row::STATUS_ACTIVE;
                $user->save();

                $user2role = new UsersRoles\Row();
                $user2role->userId = $user->id;
                $user2role->roleId = 2;
                $user2role->save();

                $row = new Auth\Row();
                $row->userId = $user->id;
                $row->provider = Auth\Row::PROVIDER_FACEBOOK;
                $row->foreignKey = $user_profile['id'];
                $row->token = 0;
                $row->tokenSecret = 0;
                $row->tokenType = Auth\Row::TYPE_ACCESS;
                $row->save();

                // sign in
                $user->login();
            }
        }
    } else {
        /**
         * If user doesn't allow application yet, redirect him to fb page for this.
         * After this operation we will returned to this file.
         * Is user declined app, we get param 'error' => 'access_denied'
         */

        // if user declined
        if ('access_denied' == $this->getRequest()->getParam('error', null)) {
            $this->redirectTo('users', 'signin');
        }

        $login_url = $facebook->getLoginUrl(array('scope' => 'email'));
        $this->redirect($login_url);
    }

    $this->redirectTo('index', 'index');
};
