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
 * @param array $acl
 * @return \closure
 */
function ($acl) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $callback = function () use ($acl) {
        /**
         * @var \Application\Bootstrap $this
         */
        $this->getDb()->query('DELETE FROM acl_privileges');
        foreach ($acl as $roleId => $modules) {
            foreach ($modules as $module => $privileges) {
                foreach ($privileges as $privilege => $flag) {
                    $this->getDb()->query(
                        'INSERT INTO acl_privileges SET roleId = ?, module = ?, privilege = ?',
                        array($roleId, $module, $privilege)
                    );
                }
            }
        }
    };

    if ($this->getDb()->transaction($callback)) {
        $this->getCache()->deleteByTag('privileges');
        $this->getMessages()->addSuccess('All data was saved');
    } else {
        $this->getMessages()->addError('Internal Server Error');
    }

    $this->redirectTo('acl', 'index');
};
