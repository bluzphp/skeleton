<?php
/**
 * Default dashboard module/controller
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 * @return closure
 */
namespace Application;

return
/**
 * @privilege Dashboard
 *
 * @return \closure
 */
function () {
    /**
     * @var \Application\Bootstrap $this
     */
    $this->getLayout()->breadCrumbs(
        [
            __('Dashboard'),
        ]
    );
    $this->getLayout()->setTemplate('dashboard.phtml');
};
