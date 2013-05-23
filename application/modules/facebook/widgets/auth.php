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
        <img src="/img/social-icons/facebook.png" alt='Use your facebook account!' />
    </a>
    <?php
    };
?>