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

use Bluz\Proxy\Layout;
use Bluz\Proxy\Request;
use Bluz\Validator\Exception\ValidatorException;

return
/**
 * @method GET, POST
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     */
    Layout::setTemplate('small.phtml');

    $row = new Test\Row();
    $view->row = $row;


    if (Request::isPost()) {
        $crud = Test\Crud::getInstance();
        try {
            $crud->createOne(Request::getPost());
        } catch (ValidatorException $e) {
            $row = $crud->readOne(null);
            $row->setFromArray(Request::getPost());
            $result = [
                'row'    => $row,
                'errors' => $e->getErrors(),
                'method' => Request::METHOD_POST
            ];
        }


        // TODO: example without AJAX calls
    }
};
