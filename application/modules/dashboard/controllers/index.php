<?php
/**
 * Default dashboard module/controller
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 * @return closure
 */
namespace Application;

use Bluz;

return
/**
 * @privilege Dashboard
 *
 * @return \closure
 */
function () {
    /**
     * @var \Bluz\Application $this
     */
    $this->getLayout()->setTemplate('dashboard.phtml');
};
