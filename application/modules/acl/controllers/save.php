<?php
/**
 * @author   Anton Shevchuk
 * @created  10.10.11 16:48
 */

/**
 * @namespace
 */
namespace Application;

return
/**
 * @privilege Edit
 *
 * @param array $acl
 * @return void
 */
function ($acl) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $callback = function () use ($acl) {
        /**
         * @var Bootstrap $this
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

    if (empty($acl)) {
        $this->getMessages()->addError('Privileges set is empty. You can\'t remove all of them');
    } elseif ($this->getDb()->transaction($callback)) {
        $this->getCache()->deleteByTag('privileges');
        $this->getMessages()->addSuccess('All data was saved');
    } else {
        $this->getMessages()->addError('Internal Server Error');
    }

    $this->redirectTo('acl', 'index');
};
