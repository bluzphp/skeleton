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
function($acl) use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    if ($this->getDb()->transaction(function() use ($acl) {
        /**
         * @var \Bluz\Application $this
         */
        $this->getDb()->delete('acl_privileges');
        foreach ($acl as $roleId => $modules) {
            foreach ($modules as $module => $privileges) {
                foreach ($privileges as $privilege => $flag) {
                    $this->getDb()->insert('acl_privileges', array(
                        'roleId' => $roleId,
                        'module' => $module,
                        'privilege' => $privilege
                    ));
                }
            }
        }
    })) {
        $this->getMessages()->addSuccess('All data was saved');
    } else {
        $this->getMessages()->addError('Internal server error');
    }

    $this->redirectTo('acl', 'index');
};