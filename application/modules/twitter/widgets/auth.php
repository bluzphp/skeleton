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
     * @var \Application\Bootstrap $this
     */
    ?>
    <a href="<?=$this->getRouter()->url('twitter', 'auth')?>">
        <img src="<?= $this->getView()->baseUrl('/img/social-icons/twitter.png') ?>" alt='Use your twitter account!' />
    </a>
    <?php
};
