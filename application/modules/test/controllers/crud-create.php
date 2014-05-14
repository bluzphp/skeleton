<?php
/**
 * Create of CRUD
 *
 * @category Application
 *
 * @author   dark
 * @created  14.05.13 10:50
 */
namespace Application;

return
/**
 * @method GET, POST
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     */
    $this->getLayout()->setTemplate('small.phtml');

    $row = new Test\Row();
    $view->row = $row;


    if ($this->getRequest()->isPost()) {
        // TODO: example without AJAX calls
    }
};
