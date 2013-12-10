<?php
/**
 * @namespace
 */
namespace Application\Library;

use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;

/**
 * Auth Twitter
 *
 * @category Application
 * @package  Library
 *
 * @author   Petr Marchenko
 * @created  10.12.13
 *
 * @private string $domain
 * @private string $requestToken
 * @private string $accessToken
 * @private string $oauthPlugin
 *
 */
class Twitter extends Client
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
     * @param string $callbackUrl
     * @return Client - Create a GET request: $client->get($uri, array $headers, $options)
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
     * @param string $oauthToken
     * @param string $oauthVerifier
     * @param string $oauthTokenSecret
     * @return Client - Create a GET request: $client->get($uri, array $headers, $options)
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
