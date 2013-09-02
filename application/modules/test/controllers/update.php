<?php
/**
 * Update of CRUD
 *
 * @category Application
 *
 * @author   dark
 * @created  14.05.13 10:51
 */
namespace Application;

use Bluz\Crud\ValidationException;
use Bluz\Request\AbstractRequest;
use Bluz\Request\HttpRequest;

return
/**
 * @method GET
 * @method PUT
 * @method POST
 *
 * @route /test/update/{$id}
 *
 * @param int $id
 * @param array $data
 * @return \closure
 */
function ($id, $data = array()) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     */
    $this->getLayout()->setTemplate('small.phtml');

    $rest = new Test\Rest();

    try {
        $result = $rest();

        switch ($rest->getMethod()) {
            default:
            case (AbstractRequest::METHOD_GET):
                $view->row = $result;
                $view->method = 'put';
                return 'create.phtml';
            case (AbstractRequest::METHOD_PUT):
                $this->getMessages()->addSuccess("Raw was updated");
                $this->redirectTo('test', 'update', ['id' => $id]);
                return false; // disable view and layout
            case (AbstractRequest::METHOD_POST):
                $this->getMessages()->addSuccess("Raw was created");
                $this->redirectTo('test', 'update', ['id' => $result]);
                return false; // disable view and layout
        }
    } catch (ValidationException $e) {
        // validation error
        $view->method = $rest->getMethod();
        return 'create.phtml';
    }
};
