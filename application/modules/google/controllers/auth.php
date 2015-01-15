<?php

namespace Application;

use Bluz\Proxy\Config;
use Bluz\Proxy\Router;
use Google\Client;

return

function () {
    /**
     * @var Bootstrap $this
     */
    $config = Config::getData('auth', 'google');
    $callbackUrl = Router::getFullUrl('google', 'callback');

    $googleAuth = new Client($config);
    $this->redirect($googleAuth->getAuthUrl($callbackUrl));
};
