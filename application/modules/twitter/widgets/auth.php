<?php
/**
 * @author   Anton Shevchuk
 * @created  22.10.12 18:40
 */
namespace Application;

use Bluz\Proxy\Router;

return
/**
 * @return \closure
 */
function () {
    /**
     * @var Bootstrap $this
     */
    ?>
    <a href="<?=Router::getUrl('twitter', 'auth')?>" class="btn fa fa-twitter-square fa-2x"></a>
    <?php
};
