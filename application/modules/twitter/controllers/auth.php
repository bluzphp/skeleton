<?php
/**
 * Twitter Auth controller
 *
 * @author   Anton Shevchuk
 * @created  23.10.12 18:10
 */
namespace Application;

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
    $config = $this->getConfigData('auth', 'twitter');
    $callbackUrl = $this->getRouter()->getFullUrl('twitter', 'callback');

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
        $this->getMessages()->addError($e->getMessage());
    }
};
