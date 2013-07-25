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
     * @var \Bluz\View\View $view
     */
    ?>
    <a href="<?=$this->getRouter()->url('facebook', 'auth')?>">
        <img src="<?= $this->getView()->baseUrl('/img/social-icons/facebook.png') ?>"
             alt="<?= _("Use your facebook account!")?>" />
    </a>
    <?php
};
