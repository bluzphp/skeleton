<?php
/**
 * @namespace
 */
namespace Application\Auth;

use Guzzle\Http\Client;

/*
 * Auth Twitter
 *
 * @category Application
 * @package  Auth
 *
 * @property string $REQUEST_TOKEN_URL
 * @property string $ACCESS_TOKEN_URL
 * @property string $oauth_nonce
 * @property string $oauth_timestamp
 * @property array $data
 *
 */
class Twitter extends Client
{
    /*
     * Constant.
     *
     * @var url - get request_token in Twitter.
     */
    protected $REQUEST_TOKEN_URL = 'https://api.twitter.com/oauth/request_token/';

    /*
     * Constant.
     *
     * @var url - get access_token in Twitter.
     */
    protected $ACCESS_TOKEN_URL = 'https://api.twitter.com/oauth/access_token';

    /*
     * Setting.
     *
     * @var oauth_nonce - for signature generation.
     */
    protected $oauth_nonce = null;

    /*
     * Setting.
     *
     * @var oauth_nonce - for signature generation.
     */
    protected $oauth_timestamp = null;

    /*
     * Setting.
     *
     * @var data - setting Twitter applications.
     */
    protected $data = array();

    /*
     * __construct
     *
     * @param array $config
     * @return Twitter
     */
    public function __construct($config)
    {
        parent::__construct();

        /*
         * verification setting.
         */

        if (!$config || !isset($config['consumer_key']) || empty($config['consumer_key'])
             || !isset($config['consumer_secret']) || empty($config['consumer_secret'])) {
             throw new \Exception('Twitter authorization is not configured');
        }

        $this->data = $config;
        /*
         * Generation param for signature.
         */
        $this->oauth_nonce = md5(uniqid(rand(), true));
        $this->oauth_timestamp = time();

    }

    /*
     * getData
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /*
     * Create for get Url Oauth
     * @param  string $callbackUrl The callback url
     * @return string
     */
    public function getUrlOauth($callbackUrl)
    {
        $oauth_base_text = "GET&"
            . urlencode($this->REQUEST_TOKEN_URL) . "&"
            . urlencode("oauth_callback=" . urlencode($callbackUrl) . "&")
            . urlencode("oauth_consumer_key=" . $this->data['consumer_key'] . "&")
            . urlencode("oauth_nonce=" . $this->oauth_nonce . "&")
            . urlencode("oauth_signature_method=HMAC-SHA1&")
            . urlencode("oauth_timestamp=" . $this->oauth_timestamp . "&")
            . urlencode("oauth_version=1.1");

        $key = $this->data['consumer_secret']."&";
        $oauth_signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        $url = $this->REQUEST_TOKEN_URL
            . '?oauth_callback=' . urlencode($callbackUrl)
            . '&oauth_consumer_key=' . $this->data['consumer_key']
            . '&oauth_nonce=' . $this->oauth_nonce
            . '&oauth_signature=' . urlencode($oauth_signature)
            . '&oauth_signature_method=HMAC-SHA1'
            . '&oauth_timestamp=' . $this->oauth_timestamp
            . '&oauth_version=1.1';

        return $url;

    }

    /*
     * Create for get Url Token
     * @param  string $oauth_token
     * @param  string $oauth_verifier
     * @param  string $oauthTokenSecret
     * @return string
     */
    public function getUrlToken($oauth_token, $oauth_verifier, $oauthTokenSecret)
    {
        $oauth_base_text = "GET&"
            . urlencode($this->ACCESS_TOKEN_URL)."&"
            . urlencode("oauth_consumer_key=".$this->data['consumer_key']."&")
            . urlencode("oauth_nonce=".$this->oauth_nonce."&")
            . urlencode("oauth_signature_method=HMAC-SHA1&")
            . urlencode("oauth_token=".$oauth_token."&")
            . urlencode("oauth_timestamp=".$this->oauth_timestamp."&")
            . urlencode("oauth_verifier=".$oauth_verifier."&")
            . urlencode("oauth_version=1.1");

        $key = $this->data['consumer_secret']."&".$oauthTokenSecret;
        $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        $url = $this->ACCESS_TOKEN_URL
            . '?oauth_nonce='.$this->oauth_nonce
            . '&oauth_signature_method=HMAC-SHA1'
            . '&oauth_timestamp='.$this->oauth_timestamp
            . '&oauth_consumer_key='.$this->data['consumer_key']
            . '&oauth_token='.urlencode($oauth_token)
            . '&oauth_verifier='.urlencode($oauth_verifier)
            . '&oauth_signature='.urlencode($signature)
            . '&oauth_version=1.1';

        return $url;

    }
}