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
 * @method POST
 * @param array $data
 * @return \closure
 */
function ($data = array()) use ($view) {
    /**
     * @var \Bluz\Application $this
     */
    $this->getLayout()->setTemplate('small.phtml');
    $row = new Test\Row();

    if ($this->getRequest()->isPost()) {
        try {
            // simple validation here
            $errors = [];
            if (!isset($data['name'])) {
                $errors['name'] = "Name can't be empty";
            } elseif (!preg_match('/^[a-z][a-z0-9]+$/i', $data['name'])) {
                $errors['name'] = "Invalid name";
            }

            if (!isset($data['email'])) {
                $errors['email'] = "Email can't be empty";
            } elseif (!preg_match('/^[a-z0-9-_.]+@[a-z0-9-_.]+$/i', $data['email'])) {
                $errors['email'] = "Invalid email format";
            }

            if (!isset($data['status']) or !in_array($data['status'], ['active', 'delete', 'disable'])) {
                $errors['status'] = "Please choose user status";
            }

            $row->name = $data['name'];
            $row->email = $data['email'];
            $row->status = $data['status'];

            if (sizeof($errors)) {
                $view->formId = 'form'; // form UID
                $view->errors = $errors;
                $view->callback = 'bluz.form.notices';
                throw new Exception("Please fix all errors");
            }

            if ($id = $row->save()) {
                $this->getMessages()->addSuccess("Test record was save");
                $this->redirectTo('test', 'read', ['id'=>$id]);
            }


        } catch (Exception $e) {
            $this->getMessages()->addError($e->getMessage());
        }
    }
    $view->row = $row;
};
