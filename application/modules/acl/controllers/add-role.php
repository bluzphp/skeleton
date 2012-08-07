<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */
namespace Application;

return
/**
 * @privilege Edit
 *
 * @return \closure
 */
function($name = null) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     * @var \Bluz\Request\HttpRequest $request
     */
    // data was sent
    if ($name !== null) {
        try {
            // validate of name
            if (empty($name)) {
                throw new Exception('Please, input role name');
            }
            if (!preg_match('/[a-zA-Z0-9-_]+/', $name)) {
                throw new Exception('Role name should be alphanumeric');
            }
            if ($role = Roles\Table::getInstance()->findRowWhere(['name'=>$name])) {
                throw new Exception('Role "'.$name.'" already exists');
            }

            // insert data to Db
            $this->getDb()->insert('acl_roles', ['name'=>$name]);
        } catch (\Exception $e) {
            // send validation error to browser
            $this->getMessages()->addError($e->getMessage());
            return false;
        }
        $this->getMessages()->addSuccess('Role was added');
        $this->reload();
    }
};