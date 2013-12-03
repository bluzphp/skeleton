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
use Application\Auth\Twitter;
use Guzzle\Common\Exception\GuzzleException;

return
/**
 * @return \closure
 */
function () {

    /*
     * Get config params
     * @var array $twitter
     */
    $twitter = $this->getConfigData('auth', 'twitter');
    $callbackUrl = $this->getRouter()->getFullUrl('twitter', 'callback');

    $twitterAuth = new Twitter($twitter);
    /*
     * Get url.
     * @return string $url
     */
    $url = $twitterAuth->getUrlOauth($callbackUrl);

    /*
     * Try get oauth_token in Twitter.
     * If the attempt is successful, redirect to twitter authenticate.
     */
    try {
        $request = $twitterAuth->get($url)->send();
        parse_str($request->getBody(), $result);

        if (!$result || !isset($result['oauth_token']) || empty($result['oauth_token']))
        {
             throw new \Exception('Twitter authorization is not configured');
        }

        $this->redirect('https://api.twitter.com/oauth/authenticate?oauth_token='.$result['oauth_token']);

    } catch (GuzzleException $e) {
        $this->getMessages()->addError($e->getMessage());
    }

    return false;
};
