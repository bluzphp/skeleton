<?php
/**
 * Twitter Auth controller
 *
 * @author   Anton Shevchuk
 * @created  23.10.12 18:10
 */
namespace Application;

use Bluz\Proxy\Config;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Router;
use Guzzle\Common\Exception\GuzzleException;
use Twitter\Client;

return
/**
 * @return \closure
 */
function () {

    /**
     * Get config params
     * @var array $config
     * @var string $callbackUrl
     */
    $config = Config::getData('auth', 'twitter');
    $callbackUrl = Router::getFullUrl('twitter', 'callback');

    /**
     * Create new Twitter\Client
     */
    $twitterAuth = new Client($config);

    /**
     * Try send request to API
     * if received oauth_token then redirect
     */
    try {
        $token = $twitterAuth->getOauthRequestToken($callbackUrl);
        $this->redirect('https://api.twitter.com/oauth/authenticate?oauth_token='.$token);
    } catch (GuzzleException $e) {
        Messages::addError($e->getMessage());
    }
};
