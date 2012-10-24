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
     * @var Bluz\Application $this
     */
    // рандомная строка (для безопасности)
    $oauth_nonce = md5(uniqid(rand(), true));

    // время когда будет выполняться запрос (в секундах)
    $oauth_timestamp = time(); // 1310727371

    /**
     * Обратите внимание на использование функции urlencode и расположение амперсандов.
     * Если поменяете положение параметров oauth_... или уберете где-нибудь urlencode - получите ошибку
     */
    $options = $this->getConfigData('auth', 'twitter');
    $oauth_base_text = "GET&"
                     . urlencode("https://api.twitter.com/oauth/request_token")."&"
                     . urlencode("oauth_callback=".urlencode("http://bluz.dark.php4.nixsolutions.com/twitter/callback/")."&")
                     . urlencode("oauth_consumer_key=".$options['consumerKey']."&")
                     . urlencode("oauth_nonce=".$oauth_nonce."&")
                     . urlencode("oauth_signature_method=HMAC-SHA1&")
                     . urlencode("oauth_timestamp=".$oauth_timestamp."&")
                     . urlencode("oauth_version=1.0");

    $key = $options['consumerSecret']."&"; // На конце должен быть амперсанд & !!!

    $oauth_signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

    $url = 'https://api.twitter.com/oauth/request_token'
         . '?oauth_callback='.urlencode("http://bluz.dark.php4.nixsolutions.com/twitter/callback/")
         . '&oauth_consumer_key='.$options['consumerKey']
         . '&oauth_nonce='.$oauth_nonce
         . '&oauth_signature='.urlencode($oauth_signature)
         . '&oauth_signature_method=HMAC-SHA1'
         . '&oauth_timestamp='.$oauth_timestamp
         . '&oauth_version=1.0';

    /**
     * Получить результат GET-запроса будем функцией file_get_contents
     * можно и даже лучше использовать curl, но здесь и эта функция справляется отлично
     */
    if (!$response = @file_get_contents($url)) {
        throw new Exception("Invalid settings for Twitter Auth Provider", 500);
    }
    // если все сделано правильно, $response будет содержать нечто подобное:
    // oauth_token=DZmWEaKh7EqOJKScI48IgYMxYyFF2riTyD5N9wqTA&oauth_token_secret=NuAL0AvzocI9zxO7VnVPrNEcb9EW8kwpwJmcqg5pMWg&oauth_callback_confirmed=true
    //
    // Если что-то не так, будет выведено следующее:
    // Failed to validate oauth signature and token
    // Самая распространенная ошибка, означающая, что в большинстве случаев
    // подпись oauth_signature сформирована неправильно.
    // Еще раз внимательно просмотрите как формируется строка и кодируется oauth_signature,
    // сверьте с примером использования функций urlencode

    parse_str($response, $result);

    if (isset($result['oauth_token'])) {
        $this->getSession()->oauthTokenSecret = $result['oauth_token_secret'];
        $this->redirect('https://api.twitter.com/oauth/authorize?oauth_token='.$result['oauth_token']);
    }

    return false;
};
