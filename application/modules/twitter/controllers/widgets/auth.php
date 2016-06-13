<?php
/**
 * @author   Anton Shevchuk
 * @created  22.10.12 18:40
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Router;

/**
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    ?>
    <a href="<?=Router::getUrl('auth', 'auth', ['provider'=> 'twitter'])?>"
       class="provider btn fa fa-twitter-square fa-2x"></a>
    <?php
};
