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

return
/**
 * @return \closure
 */
function() {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $options = $this->getConfigData('auth', 'twitter');
    // check configuration
    if (!$options || !isset($options['consumerKey']) || empty($options['consumerKey'])
        || !isset($options['consumerSecret']) || empty($options['consumerSecret'])) {
        throw new Exception('Twitter authorization is not configured');
    }

    // callback
    $callbackUrl = $this->getRouter()->getFullUrl('twitter', 'callback');

    // random string
    $oauth_nonce = md5(uniqid(rand(), true));

    // timestamp
    $oauth_timestamp = time();

    /**
     * Build base text
     */
    $oauth_base_text = "GET&"
                     . urlencode("https://api.twitter.com/oauth/request_token")."&"
                     . urlencode("oauth_callback=".urlencode($callbackUrl)."&")
                     . urlencode("oauth_consumer_key=".$options['consumerKey']."&")
                     . urlencode("oauth_nonce=".$oauth_nonce."&")
                     . urlencode("oauth_signature_method=HMAC-SHA1&")
                     . urlencode("oauth_timestamp=".$oauth_timestamp."&")
                     . urlencode("oauth_version=1.0");

    $key = $options['consumerSecret']."&";

    $oauth_signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

    $url = 'https://api.twitter.com/oauth/request_token'
         . '?oauth_callback='.urlencode($callbackUrl)
         . '&oauth_consumer_key='.$options['consumerKey']
         . '&oauth_nonce='.$oauth_nonce
         . '&oauth_signature='.urlencode($oauth_signature)
         . '&oauth_signature_method=HMAC-SHA1'
         . '&oauth_timestamp='.$oauth_timestamp
         . '&oauth_version=1.0';

    // get response from Twitter service
    if (!$response = @file_get_contents($url)) {
        throw new Exception("Invalid settings for Twitter Auth Provider", 500);
    }
    // we should retrieve
    // oauth_token=DZmWEaKh7EqOJKScI48IgYMxYyFF2riTyD5N9wqTA&oauth_token_secret=NuAL0AvzocI9zxO7VnVPrNEcb9EW8kwpwJmcqg5pMWg&oauth_callback_confirmed=true

    // parse response to array
    parse_str($response, $result);

    // check response
    if (isset($result['oauth_token'])) {
        // save secret token to session
        $this->getSession()->oauthTokenSecret = $result['oauth_token_secret'];
        $this->redirect('https://api.twitter.com/oauth/authorize?oauth_token='.$result['oauth_token']);
    } else {
        throw new Exception("Invalid response", 500);
    }

    // disable view
    return false;
};
