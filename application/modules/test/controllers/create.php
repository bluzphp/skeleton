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
 * @method GET
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
     */
    $this->getLayout()->setTemplate('small.phtml');

    $row = new Test\Row();
    $view->row = $row;

    $view->method = 'post';
    return;
};
