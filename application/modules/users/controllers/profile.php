<?php
/**
 * View User Profile
 *
 * @author   Anton Shevchuk
 * @created  01.09.11 13:15
 */
namespace Application;
use Bluz;

return
/**
 * @param int $id
 *
 * @privilege ViewProfile
 *
 * @return \closure
 */
function($id = null) use ($view) {

    /**
     * @var Bluz\Application $this
     * @var Bluz\View\View $view
     */
    $this->getLayout()->title = 'User Profile';

    // try to load profile of current user
    if (!$id && $this->getAuth()->getIdentity()) {
        $id = $this->getAuth()->getIdentity()->id;
    }

    /**
     * @var \Application\Users\Row $user
     */
    $user = $this->getDb()->fetchObject('SELECT * FROM users WHERE id = ?', array($id), 'Application\Users\Row');

    if (!$user) {
        throw new \Exception('User not found', 404);
    } else {
        $view->user = $user;
    }
};