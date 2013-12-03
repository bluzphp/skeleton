<?php
/**
 * Twitter Auth controller
 *
 * @author   Anton Shevchuk
 * @created  23.10.12 18:10
 */
namespace Application;

use Application\Auth\Table;
use Application\Users;
use Application\Users\Table as Table1;
use Bluz;

use Application\Auth\Twitter;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Common\Exception\GuzzleException;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */

    /*
     * Get config params.
     */
    $twitter = $this->getConfigData('auth', 'twitter');
    $oauth_token = $this->getRequest()->getParam('oauth_token');
    $oauth_verifier = $this->getRequest()->getParam('oauth_verifier');
    $oauthTokenSecret = $this->getSession()->oauthTokenSecret;

    /*
     * @var Twitter $twitterAuth
     */
    $twitterAuth = new Twitter($twitter);

    /*
     * Get url - For me information on the token.
     * @return string $url
     */
    $url = $twitterAuth->getUrlToken($oauth_token, $oauth_verifier, $oauthTokenSecret);

    /*
     * Try get information on the token.
     */

    try {
        $request = $twitterAuth->get($url)->send();
        parse_str($request->getBody(), $result);

        if (!$result || !isset($result['user_id']) || empty($result['user_id']))
        {
             throw new \Exception('Twitter authorization is not configured');
        }

        /*
         * if the information is obtained.
         */

        /*
         * @var Auth\Table $authTable
         */
        $authTable = Auth\Table::getInstance();

        /*
         * Try to load previous information
         * @var /Application/Auth/Row $row
         */
        $row = $authTable->getAuthRow(Table::PROVIDER_TWITTER, $result['user_id']);

        if ($row)
        {
            /*
             * Try to sign in
             * @var Users\Row $user
             */
            $user = Users\Table::findRow($row->userId);

            /*
             * Check the status of the user
             */
            if ($user->status != Table1::STATUS_ACTIVE) {
                $this->getMessages()->addError('User is not active');
                $this->redirectTo('index', 'index');
            }

            /*
             * Update tokens
             */
            $row->token = $result['oauth_token'];
            $row->tokenSecret = $result['oauth_token_secret'];
            $row->tokenType = Table::TYPE_ACCESS;
            $row->save();

            /*
             * sign in.
             */
            $user->login();
        } else {

            /*
             * if user already signed - link new auth provider to account
             * another - create new user
             */
            if (!$user = $this->getAuth()->getIdentity())
            {
                /*
                 * Create new user
                 * @var Users\Row $user
                 */
                $user = new Users\Row();
                $user->login = $result['screen_name'];
                $user->status = Table1::STATUS_ACTIVE;
                $user->save();

                /*
                 * Set default role
                 * @var UsersRoles\Row $user2role
                 */
                $user2role = new UsersRoles\Row();
                $user2role -> userId = $user->id;
                $user2role -> roleId = 2;
                $user2role -> save();

                /*
                 * sign in.
                 */
                $user->login();
            }

            /*
             * @var Auth\Table $row
             * save user info.
             */
            $row = new Auth\Row();
            $row->userId = $user->id;
            $row->provider = Table::PROVIDER_TWITTER;
            $row->foreignKey = $result['user_id'];
            $row->token = $result['oauth_token'];
            $row->tokenSecret = $result['oauth_token_secret'];
            $row->tokenType = Table::TYPE_ACCESS;
            $row->save();
        }

        $this->getMessages()->addNotice('You are signed');
        $this->redirectTo('index', 'index');

    } catch (GuzzleException $e) {
        $this->getMessages()->addError($e->getMessage());
    }

    return false;
};
