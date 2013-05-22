<?php
/**
 * @author   Anton Shevchuk
 * @created  22.10.12 18:40
 */
namespace Application;
return
    /**
     * @return \closure
     */
    function () {
        /**
         * @var \Bluz\Application $this
         */
        ?>
    <a href="<?=$this->getRouter()->url('facebook', 'auth')?>">
        <i class="icon-facebook icon-2x" title="Use your facebook account!"></i>
    </a>
    <?php
    };
?>