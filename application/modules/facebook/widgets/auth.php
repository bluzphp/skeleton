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
     * @var Controller $this
     */
    ?>
    <a href="<?=Router::getUrl('facebook', 'auth')?>" class="btn fa fa-facebook-square fa-2x"></a>
    <?php
};
