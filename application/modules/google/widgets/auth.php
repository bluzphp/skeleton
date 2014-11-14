<?php

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
        <a href="<?=Router::getUrl('google', 'auth')?>" class="btn fa fa-google-plus-square fa-2x"></a>
    <?php
    };
