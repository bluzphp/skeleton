<?php
/**
 * @namespace
 */
namespace Application\Library\Twitter;

use Guzzle\Http\Client as GuzzleClient;;
use Guzzle\Plugin\Oauth\OauthPlugin;

/**
 * Auth Twitter
 *
 * @category Application
 * @package  Library
 *
 * @private string $domain
 * @private string $requestToken
 * @private string $accessToken
 * @private string $oauthPlugin
 *
 */
class Client extends GuzzleClient
{

    private $domain = 'https://api.twitter.com/';
    private $requestToken = 'oauth/request_token';
    private $accessToken = 'oauth/access_token';
    private $oauthPlugin = null;

    /**
     * __construct
     *
     * @param array $config
     * @return Twitter
     */
    public function __construct($config)
    {
        /**
         * verification setting.
         */

        if (!$config || !isset($config['consumer_key']) || empty($config['consumer_key'])
             || !isset($config['consumer_secret']) || empty($config['consumer_secret'])) {
             throw new \Exception('Twitter authorization is not configured');
        }

        parent::__construct($this->domain);

        /**
         * Create Object.
         *
         * @param array $config
         * @return OauthPlugin
         */
        $this->oauthPlugin = new OauthPlugin($config);
        $this->addSubscriber($this->oauthPlugin);


    }

    /**
     * getOauthRequestToken
     *
     * $callbackUrl = $this->getRouter()->getFullUrl('twitter', 'callback');
     * $oauthRequestToken = $twitterAuth->getOauthRequestToken($callbackUrl);     *
     *
     * @param string $callbackUrl
     * @return Guzzle\Http\Client
     */
    public function getOauthRequestToken($callbackUrl)
    {
        $response = $this->get(
            $this->requestToken,
            null,
            array(
                'query' => array(
                    'oauth_callback' => $callbackUrl
                )
            )
        );

        return $response;
    }

    /**
     * getOauthAccessToken
     *
     * $oauth_token = $this->getRequest()->getParam('oauth_token');
     * $oauth_verifier = $this->getRequest()->getParam('oauth_verifier');
     * $oauthTokenSecret = $this->getSession()->oauthTokenSecret;
     * $oauthRequestToken = $twitterAuth->getOauthAccessToken($oauth_token, $oauth_verifier, $oauthTokenSecret);
     *
     * @param string $oauthToken
     * @param string $oauthVerifier
     * @param string $oauthTokenSecret
     * @return Guzzle\Http\Client
     */
    public function getOauthAccessToken($oauthToken, $oauthVerifier, $oauthTokenSecret)
    {
        $response = $this->get(
            $this->accessToken,
            null,
            array(
                'query'   => array(
                    'oauth_token' => $oauthToken,
                    'oauth_verifier' => $oauthVerifier,
                    'oauth_consumer_key' => $oauthTokenSecret
                )
            )
        );

        return $response;
    }
}
