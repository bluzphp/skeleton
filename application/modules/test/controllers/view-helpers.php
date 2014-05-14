<?php
/**
 * Demo of View helpers
 *
 * @category Application
 *
 * @author   dark
 * @created  14.05.13 16:12
 */
namespace Application;

return
/**
 * @param bool $sex
 * @param string $car
 * @param bool $remember
 * @return \closure
 */
function ($sex = false, $car = 'none', $remember = false) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'View Form Helpers',
        ]
    );
    /**
     * @var Bootstrap $this
     */
    $view->sex = $sex;
    $view->car = $car;
    $view->remember = $remember;

    if ($this->getRequest()->isPost()) {
        $view->params = $this->getRequest()->getAllParams();
    }
};
