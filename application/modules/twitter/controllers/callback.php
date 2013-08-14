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
function () {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    // process "denied" response
    if ($this->getRequest()->getParam('denied')) {
        $this->redirectTo('index', 'index');
    }

    $options = $this->getConfigData('auth', 'twitter');

    // random string
    $oauth_nonce = md5(uniqid(rand(), true));

    // timestamp
    $oauth_timestamp = time(); // 1310727371
    $oauth_token = $this->getRequest()->getParam('oauth_token');
    $oauth_verifier = $this->getRequest()->getParam('oauth_verifier');

    $oauthTokenSecret = $this->getSession()->oauthTokenSecret;

    // build base text
    $oauth_base_text = "GET&"
        . urlencode('https://api.twitter.com/oauth/access_token')."&"
        . urlencode("oauth_consumer_key=".$options['consumerKey']."&")
        . urlencode("oauth_nonce=".$oauth_nonce."&")
        . urlencode("oauth_signature_method=HMAC-SHA1&")
        . urlencode("oauth_token=".$oauth_token."&")
        . urlencode("oauth_timestamp=".$oauth_timestamp."&")
        . urlencode("oauth_verifier=".$oauth_verifier."&")
        . urlencode("oauth_version=1.0");

    // create key (Consumer secret + '&' + oauth_token_secret)
    $key = $options['consumerSecret']."&".$oauthTokenSecret;

    // build auth_signature
    $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

    // build URL
    $url = 'https://api.twitter.com/oauth/access_token'
        . '?oauth_nonce='.$oauth_nonce
        . '&oauth_signature_method=HMAC-SHA1'
        . '&oauth_timestamp='.$oauth_timestamp
        . '&oauth_consumer_key='.$options['consumerKey']
        . '&oauth_token='.urlencode($oauth_token)
        . '&oauth_verifier='.urlencode($oauth_verifier)
        . '&oauth_signature='.urlencode($signature)
        . '&oauth_version=1.0';

    // send request
    if (!$response = @file_get_contents($url)) {
        throw new Exception("Invalid Twitter token", 401);
    }

    // parse result
    parse_str($response, $result);
    /*
        array (size=4)
          'oauth_token' => string '****' (length=49)
          'oauth_token_secret' => string '****' (length=42)
          'user_id' => string '********' (length=8)
          'screen_name' => string '*****' (length=13)
    */

    /**
     * @var Auth\Table $authTable
     */
    $authTable = Auth\Table::getInstance();

    // try to load previous information
    /* @var /Application/Auth/Row $row */
    $row = $authTable->getAuthRow(Auth\Row::PROVIDER_TWITTER, $result['user_id']);


    if ($row) {
        // try to sign in
        /** @var Users\Row $user */
        $user = Users\Table::findRow($row->userId);

        if ($user->status != Users\Row::STATUS_ACTIVE) {
            $this->getMessages()->addError('User is not active');
            $this->redirectTo('index', 'index');
        }

        // update tokens
        $row->token = $result['oauth_token'];
        $row->tokenSecret = $result['oauth_token_secret'];
        $row->tokenType = Auth\Row::TYPE_ACCESS;
        $row->save();



        // sign in
        $user->login();
    } else {

        // if user already signed - link new auth provider to account
        // another - create new user
        if (!$user = $this->getAuth()->getIdentity()) {
            // create new user
            $user = new Users\Row();
            $user->login = $result['screen_name'];
            $user->status = Users\Row::STATUS_ACTIVE;
            $user->save();

            // set default role
            $user2role = new UsersRoles\Row();
            $user2role -> userId = $user->id;
            $user2role -> roleId = 2;
            $user2role -> save();

            // sign in
            $user->login();
        }

        $row = new Auth\Row();
        $row->userId = $user->id;
        $row->provider = Auth\Row::PROVIDER_TWITTER;
        $row->foreignKey = $result['user_id'];
        $row->token = $result['oauth_token'];
        $row->tokenSecret = $result['oauth_token_secret'];
        $row->tokenType = Auth\Row::TYPE_ACCESS;
        $row->save();
    }

    $this->getMessages()->addNotice('You are signed');
    $this->redirectTo('index', 'index');
    return false;
};
