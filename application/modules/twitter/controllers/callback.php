<?php
/**
 * Twitter Auth Сфддифсл controller
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
     * @var Bluz\Application $this
     */
    if ($this->getRequest()->getParam('denied')) {
        $this->redirectTo('index', 'index');
    }


    // рандомная строка (для безопасности)
    $oauth_nonce = md5(uniqid(rand(), true));

    // время когда будет выполняться запрос (в секундах)
    $oauth_timestamp = time(); // 1310727371
    $oauth_token = $this->getRequest()->getParam('oauth_token');
    $oauth_verifier = $this->getRequest()->getParam('oauth_verifier');

    $options = $this->getConfigData('auth', 'twitter');

    $oauthTokenSecret = $this->getSession()->oauthTokenSecret;

    // ПОРЯДОК ПАРАМЕТРОВ ДОЛЖЕН БЫТЬ ИМЕННО ТАКОЙ!
    // Т.е. сперва oauth_callback -> oauth_consumer_key -> ... -> oauth_version.
    $oauth_base_text = "GET&";
    $oauth_base_text .= urlencode('https://api.twitter.com/oauth/access_token')."&";
    $oauth_base_text .= urlencode("oauth_consumer_key=".$options['consumerKey']."&");
    $oauth_base_text .= urlencode("oauth_nonce=".$oauth_nonce."&");
    $oauth_base_text .= urlencode("oauth_signature_method=HMAC-SHA1&");
    $oauth_base_text .= urlencode("oauth_token=".$oauth_token."&");
    $oauth_base_text .= urlencode("oauth_timestamp=".$oauth_timestamp."&");
    $oauth_base_text .= urlencode("oauth_verifier=".$oauth_verifier."&");
    $oauth_base_text .= urlencode("oauth_version=1.0");

    // Формируем ключ (Consumer secret + '&' + oauth_token_secret)
    $key = $options['consumerSecret']."&".$oauthTokenSecret;

    // Формируем auth_signature
    $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

    // Формируем GET-запрос
    $url = 'https://api.twitter.com/oauth/access_token';
    $url .= '?oauth_nonce='.$oauth_nonce;
    $url .= '&oauth_signature_method=HMAC-SHA1';
    $url .= '&oauth_timestamp='.$oauth_timestamp;
    $url .= '&oauth_consumer_key='.$options['consumerKey'];
    $url .= '&oauth_token='.urlencode($oauth_token);
    $url .= '&oauth_verifier='.urlencode($oauth_verifier);
    $url .= '&oauth_signature='.urlencode($signature);
    $url .= '&oauth_version=1.0';

    // Выполняем запрос
    if (!$response = @file_get_contents($url)) {
        throw new Exception("Invalid Twitter token", 401);
    }

    // Парсим результат запроса
    parse_str($response, $result);

    // Получаем идентификатор Твиттер-пользователя из результата запроса
/*
    array (size=4)
      'oauth_token' => string '****' (length=49)
      'oauth_token_secret' => string '****' (length=42)
      'user_id' => string '********' (length=8)
      'screen_name' => string '*****' (length=13)
*/
    $authTable = Auth\Table::getInstance();
    /*
        userId bigint(20) unsigned NOT NULL
        provider varchar(255) NOT NULLLDAP/password/OpenId etc
        foreignKey varchar(255) NULLCan be NULL for Table provider
        token varchar(64) NOT NULLIt's pass for Equals provider
        tokenSecret varchar(64) NOT NULLIt's salt for Equals provider
        tokenType enum('request','access') NOT NULL
        created
    */

    // try to load previous information
    /* @var /Application/Auth/Row $row */
    $row = $authTable->findRow([
        'provider' => 'twitter',
        'foreignKey' => $result['user_id']
    ]);

    if ($row) {
        // update tokens
        $row->token = $result['oauth_token'];
        $row->tokenSecret = $result['oauth_token_secret'];
        $row->tokenType = Auth\Row::TYPE_ACCESS;
        $row->save();

        // try to sign in
        $usersTable = Users\Table::getInstance();

        $user = $usersTable -> findRow($row->userId);

        // sign in
        $this->getAuth()->setIdentity($user);
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
            $user2role = new UsersToRoles\Row();
            $user2role -> userId = $user->id;
            $user2role -> roleId = 2;
            $user2role -> save();

            // sign in
            $this->getAuth()->setIdentity($user);

        }

        $row = new Auth\Row();
        $row->userId = $user->id;
        $row->provider = 'twitter';
        $row->foreignKey = $result['user_id'];
        $row->token = $result['oauth_token'];
        $row->tokenSecret = $result['oauth_token_secret'];
        $row->tokenType = Auth\Row::TYPE_ACCESS;
        $row->save();
    }

    $this->redirectTo('index', 'index');
    return false;
};
