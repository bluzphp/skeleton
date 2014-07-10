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

use Bluz\Crud\ValidationException;
use Bluz\Http\Request;
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
    $this->getLayout()->setTemplate('small.phtml');

    $row = new Test\Row();
    $view->row = $row;


    if ($this->getRequest()->isPost()) {
        $crud = Test\Crud::getInstance();
        try {
            $crud->createOne($this->getRequest()->getPost());
        } catch (ValidatorException $e) {
            $row = $crud->readOne(null);
            $row->setFromArray($this->getRequest()->getPost());
            $result = [
                'row'    => $row,
                'errors' => $e->getErrors(),
                'method' => Request::METHOD_POST
            ];
        }


        // TODO: example without AJAX calls
    }
};
