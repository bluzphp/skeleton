<?php

namespace Application\Google;

use Application\Auth;
use Application\Users;
use Bluz\Proxy\Config;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Router;
use Bluz\Proxy\Session;
use Google\Client;
use Guzzle\Common\Exception\GuzzleException;

return

function () {
    $config = Config::getData('auth', 'google');
    $code = Request::getParam('code');
    $callbackUrl = Router::getFullUrl('google', 'callback');

    $googleAuth = new Client($config);


    try {
        $googleAuth->getOauthAccessToken($code, $callbackUrl);
        $result = $googleAuth->getUserInfo();

        $authTable = Auth\Table::getInstance();
        $row = $authTable->getAuthRow(Auth\Table::PROVIDER_GOOGLE, $result['id']);

        if ($row) {
            $user = Users\Table::findRow($row->userId);

            /**
             * Check the status of the user
             */
            if ($user->status != Users\Table::STATUS_ACTIVE) {
                Messages::addError('User is not active');
                $this->redirectTo('index', 'index');
            }

            /**
             * Update tokens
             */
            $row->token = $googleAuth->accessToken;
            $row->save();

            $user->login();
        } else {
            if (!$user = $this->user()) {
                /**
                 * Write twitter response to session
                 */
                $result['access_token'] = $googleAuth->accessToken;
                Session::set('google', $result);
                Messages::addNotice('To finish your registration fill the form');
                $this->redirectTo('users', 'signup');
            } else {
                /**
                 * @var Auth\Row $row
                 * Users exists - save user info.
                 */
                $row = new Auth\Row();
                $row->userId = $user->id;
                $row->provider = Auth\Table::PROVIDER_GOOGLE;
                $row->foreignKey = $result['id'];
                $row->token = $googleAuth->accessToken;
                $row->tokenSecret = 0;
                $row->tokenType = Auth\Table::TYPE_ACCESS;
                $row->save();
            }
        }

        Messages::addNotice('You are signed');
        $this->redirectTo('index', 'index');
    } catch (GuzzleException $e) {
        Messages::addError($e->getMessage());
        $this->redirectTo('index', 'index');
    }
};