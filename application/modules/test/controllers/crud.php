<?php
/**
 * Example of Crud
 *
 * @author   Anton Shevchuk
 * @created  04.09.12 11:21
 */
namespace Application;

use Bluz;
use Application\Test;

return
/**
 * @return \closure
 */
function() use ($view) {
    /**
     * @var Bluz\Application $this
     */

    $crud = new Test\Crud();
    $crud->processRequest();

    $result = $crud->getResult();

    // switch statement for $result
    switch (true) {
        case (is_numeric($result)):
            // UPDATE record
            $this->useJson(true);
            $this->getMessages()->addSuccess("Row was updated");
            $this->reload();
            break;
        case (true === $result):
            // CREATE record
            $this->useJson(true);
            $this->getMessages()->addSuccess("Row was created");
            $this->reload();
            break;
        case (false === $result):
            // ERROR
            $this->useJson(true);
            $this->getMessages()->addError("Fail");
            $this->reload();
            break;
        case (null === $result):
            // GET empty record
            $view->row = $crud->getTable()->create();
            $view->method = 'post';
            break;
        case ($result instanceof \Bluz\Db\Row):
            // GET record
            $view->row = $result;
            $view->method = 'put';
            break;
        default:
            break;
    }
};
