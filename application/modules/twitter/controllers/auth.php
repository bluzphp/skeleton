<?php
/**
 * Twitter Auth controller
 *
 * @author   Anton Shevchuk
 * @created  23.10.12 18:10
 */
namespace Application;

use Bluz;
use Application\Users;
use Application\Library\Twitter;
use Guzzle\Common\Exception\GuzzleException;

return
/**
 * @return \closure
 */
function () {

    /**
     * Get config params
     * @var array $twitter
     * @var string $callbackUrl
     */
    $twitter = $this->getConfigData('auth', 'twitter');
    $callbackUrl = $this->getRouter()->getFullUrl('twitter', 'callback');

    /**
     * Create new Object Application\Auth\Twitter
     * @param array $twitter.
     */
    $twitterAuth = new Twitter($twitter);

    /**
     * getOauthRequestToken
     *
     * @param string $callbackUrl
     * @return Client - Create a GET request: $client->get($uri, array $headers, $options)
     */
    $oauthRequestToken = $twitterAuth->getOauthRequestToken($callbackUrl);

    /**
     * Try send request.
     * if received oauth_token then redirect.
     */
    try {
        $response = $oauthRequestToken->send();
        parse_str($response->getBody(), $result);
        if (!$result || !isset($result['oauth_token']) || empty($result['oauth_token'])) {
            throw new \Exception('Twitter authorization is not configured');
        }
        $this->redirect('https://api.twitter.com/oauth/authenticate?oauth_token='.$result['oauth_token']);

    } catch (GuzzleException $e) {
        $this->getMessages()->addError($e->getMessage());
    }

};
