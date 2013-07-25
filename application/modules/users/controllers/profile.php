<?php
/**
 * View User Profile
 *
 * @author   Anton Shevchuk
 * @created  01.09.11 13:15
 */
namespace Application;

use Bluz;
use Application\Users;

return
/**
 * @param int $id
 *
 * @privilege ViewProfile
 *
 * @return \closure
 */
function ($id = null) use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->title('User Profile');

    // try to load profile of current user
    if (!$id && $this->getAuth()->getIdentity()) {
        $id = $this->getAuth()->getIdentity()->id;
    }

    /**
     * @var Users\Row $user
     */
    $user = Users\Table::findRow($id);

    if (!$user) {
        throw new Exception('User not found', 404);
    } else {
        $view->user = $user;
    }
};
