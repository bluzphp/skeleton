<?php

namespace Google;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\BadResponseException;

class Client extends GuzzleClient
{
    private $domain = 'https://accounts.google.com/';
    private $requestCode = 'o/oauth2/auth';
    private $requestAccessToken = 'o/oauth2/token';
    public $accessToken = null;

    /**
     * @param string $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        if (!$config || !isset($config['client_id'], $config['client_secret'])
            || empty($config['client_id']) || empty($config['client_secret'])) {
            throw new \Exception('Google authorization is not configured');
        }

        parent::__construct($this->domain, $config);
    }

    /**
     * @param $callbackUrl
     * @return \Guzzle\Http\Url|string
     */
    public function getAuthUrl($callbackUrl)
    {
        $request = $this->get(
            $this->requestCode,
            null,
            [
                'query' => [
                    'redirect_uri' => $callbackUrl,
                    'response_type' => 'code',
                    'client_id' => $this->getConfig('client_id'),
                    'scope' => 'https://www.googleapis.com/auth/userinfo.email' .
                        ' https://www.googleapis.com/auth/userinfo.profile'
                ]
            ]
        );

        return $request->getUrl();
    }

    /**
     * @param $code
     * @param $callbackUrl
     */
    public function getOauthAccessToken($code, $callbackUrl)
    {
        if (!isset($code)) {
            throw new BadResponseException('Authorization failed');
        }

        $request = $this->post(
            $this->requestAccessToken,
            null,
            [
                'client_id' => $this->getConfig('client_id'),
                'client_secret' => $this->getConfig('client_secret'),
                'redirect_uri' => $callbackUrl,
                'grant_type'    => 'authorization_code',
                'code' => $code
            ]
        );

        $response = $request->send();
        $result = json_decode($response->getBody()->__toString(), true);
        if (isset($result['access_token'])) {
            $this->accessToken = $result['access_token'];
        } else {
            throw new BadResponseException('Authorization failed');
        }
    }

    /**
     * @return array
     */
    public function getUserInfo()
    {
        if (!isset($this->accessToken)) {
            throw new BadResponseException('Access token is missed');
        }

        $request = $this->get(
            'https://www.googleapis.com/oauth2/v1/userinfo',
            null,
            [
                'query' => [
                    'access_token' => $this->accessToken
                ]
            ]
        );

        $response = $request->send();
        $result = json_decode($response->getBody()->__toString(), true);
        if (isset($result['id'])) {
            return $result;
        } else {
            throw new BadResponseException('Invalid access token');
        }

    }
}
