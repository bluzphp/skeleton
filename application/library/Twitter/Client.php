<?php
/**
 * @namespace
 */
namespace Twitter;

use Guzzle\Http\Client as GuzzleClient;
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
    private $authorize = 'oauth/authorize';
    private $oauthPlugin = null;

    /**
     * __construct
     *
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        /**
         * verification setting
         */
        if (!$config || !isset($config['consumer_key'], $config['consumer_secret'])
            || empty($config['consumer_key']) || empty($config['consumer_secret'])) {
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
     * Get request token from API
     *
     * $callbackUrl = $this->getRouter()->getFullUrl('twitter', 'callback');
     * $oauthRequestToken = $twitterAuth->getOauthRequestToken($callbackUrl);     *
     *
     * @param string $callbackUrl
     * @throws \Exception
     * @return array
     */
    public function getOauthRequestToken($callbackUrl)
    {
        $request = $this->get(
            $this->requestToken,
            null,
            array(
                'query' => array(
                    'oauth_callback' => $callbackUrl
                )
            )
        );


        $response = $request->send();
        parse_str($response->getBody(), $result);

        if (!$result || !isset($result['oauth_token']) || empty($result['oauth_token'])) {
            throw new \Exception("System can't retrieve token. Please check configuration of Twitter authorization");
        }

        return $result['oauth_token'];
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
     * @throws \Exception
     * @return array
     */
    public function getOauthAccessToken($oauthToken, $oauthVerifier, $oauthTokenSecret)
    {
        $request = $this->get(
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

        $response = $request->send();
        parse_str($response->getBody(), $result);
        if (!$result || !isset($result['user_id']) || empty($result['user_id'])) {
            throw new \Exception('User data is not received');
        }

        return $result;
    }
}
