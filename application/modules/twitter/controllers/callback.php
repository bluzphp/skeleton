<?php
/**
 * Twitter Auth controller
 *
 * @author   Anton Shevchuk
 * @created  23.10.12 18:10
 */
namespace Application;

use Application\Auth;
use Application\Users;
use Application\UsersRoles;
use Twitter\Client;
use Guzzle\Common\Exception\GuzzleException;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */

    /**
     * Get config params.
     */
    $config = $this->getConfigData('auth', 'twitter');
    $oauthToken = $this->getRequest()->getParam('oauth_token');
    $oauthVerifier = $this->getRequest()->getParam('oauth_verifier');
    $oauthTokenSecret = $this->getSession()->oauthTokenSecret;

    /**
     * Create new Twitter\Client
     */
    $twitterAuth = new Client($config);

    /**
     * Try send request.
     * Get user info.
     * array(
     *     'oauth_token' => string
     *     'oauth_token_secret' => string
     *     'user_id' => string
     *     'screen_name' => string
     * )
     * Try to sign in
     */
    try {
        $result = $twitterAuth->getOauthAccessToken($oauthToken, $oauthVerifier, $oauthTokenSecret);

        /**
         * @var Auth\Table $authTable
         */
        $authTable = Auth\Table::getInstance();

        /**
         * Try to load previous information
         * @var /Application/Auth/Row $row
         */
        $row = $authTable->getAuthRow(Auth\Table::PROVIDER_TWITTER, $result['user_id']);

        if ($row) {
            /**
             * Try to sign in
             * @var Users\Row $user
             */
            $user = Users\Table::findRow($row->userId);

            /**
             * Check the status of the user
             */
            if ($user->status != Users\Table::STATUS_ACTIVE) {
                $this->getMessages()->addError('User is not active');
                $this->redirectTo('index', 'index');
            }

            /**
             * Update tokens
             */
            $row->token = $result['oauth_token'];
            $row->tokenSecret = $result['oauth_token_secret'];
            $row->tokenType = Auth\Table::TYPE_ACCESS;
            $row->save();

            /**
             * sign in.
             */
            $user->login();
        } else {

            /**
             * if user already signed - link new auth provider to account
             * another - create new user
             */
            if (!$user = $this->user()) {
                /**
                 * Create new user
                 * @var Users\Row $user
                 */
                $user = new Users\Row();
                $user->login = $result['screen_name'];
                $user->status = Users\Table::STATUS_ACTIVE;
                $user->save();

                /**
                 * Set default role
                 * @var UsersRoles\Row $userRole
                 */
                $userRole = new UsersRoles\Row();
                $userRole -> userId = $user->id;
                // FIXME: hardcoded role Id (2 = Member)
                $userRole -> roleId = 2;
                $userRole -> save();

                /**
                 * sign in.
                 */
                $user->login();
            }

            /**
             * @var Auth\Table $row
             * save user info.
             */
            $row = new Auth\Row();
            $row->userId = $user->id;
            $row->provider = Auth\Table::PROVIDER_TWITTER;
            $row->foreignKey = $result['user_id'];
            $row->token = $result['oauth_token'];
            $row->tokenSecret = $result['oauth_token_secret'];
            $row->tokenType = Auth\Table::TYPE_ACCESS;
            $row->save();
        }

        $this->getMessages()->addNotice('You are signed');
        $this->redirectTo('index', 'index');
    } catch (GuzzleException $e) {
        $this->getMessages()->addError($e->getMessage());
        $this->redirectTo('index', 'index');
    }

    return false;
};
