<?php
/**
 * Example of Crud
 *
 * @author   Anton Shevchuk
 * @created  04.09.12 11:21
 */
namespace Application;

use Application\Test;
use Bluz;
use Bluz\Request\AbstractRequest;

return
/**
 * @return \closure
 */
function() use ($view) {
    /**
     * @var Bluz\Application $this
     */
    $this->useJson(true);
    $crud = new Test\Crud();

    try {
        $result = $crud->processRequest()
            ->getResult();
    } catch (\Bluz\Crud\CrudException $e) {
        // all "not found" errors
        $this->getMessages()->addError($e->getMessage());
        return;
    } catch (\Bluz\Crud\ValidationException $e) {
        // validate errors
        $this->getMessages()->addError("Please fix all errors");
        $view->errors = $crud->getErrors();
        $view->callback = 'validateForm';
        return;
    }

    // switch statement for $crud->getMethod()
    switch ($crud->getMethod()) {
        case AbstractRequest::METHOD_POST:
            // CREATE record
            $this->getMessages()->addSuccess("Row was created");
            $this->reload();
            break;
        case AbstractRequest::METHOD_PUT:
            // UPDATE record
            $this->getMessages()->addSuccess("Row was updated");
            $this->reload();
            break;
        case AbstractRequest::METHOD_DELETE:
            // DELETE record
            $this->getMessages()->addSuccess("Row was deleted");
            $this->reload();
            break;
        case AbstractRequest::METHOD_GET:
        default:
            // always HTML
            $this->useJson(false);

            // enable Layout for not AJAX request
            if (!$this->getRequest()->isXmlHttpRequest()) {
                $this->useLayout(true);
            }

            // EDIT or CREATE form
            if ($result instanceof \Bluz\Db\Row) {
                // edit form
                $view->row = $result;
                $view->method = 'put';
            } else {
                // create form
                $view->row = $crud->getTable()->create();
                $view->method = 'post';
            }
            break;
    }
};
