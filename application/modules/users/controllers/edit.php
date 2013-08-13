<?php
/**
 * Edit profile controller.
 *
 * @author  Sergey Volkov
 * @created 29.05.2013 17:20
 */
namespace Application;

use Bluz;
use Application\Users;
use Application\UsersActions;
use Bluz\Request\AbstractRequest;

return
/**
 * @privilege ViewProfile
 * @return \closure
 */
function () {
    /**
     * @var \Application\Bootstrap $this
     */
    $request = $this->getRequest();

    // "catch" email changed
    $email = $request->getParam('email', null);
    $userId = $request->getParam('id', null);
    if ($email) {
        $token = UsersActions\Table::findRowWhere(['userId' => $userId, 'code'  => $email]);

        /**
         * @todo Add wrapper for cookies
         * If token is valid, get new email from cookie and save into DB.
         * At false show error message.
         */
        if ($token && isset($_COOKIE['new-email']) && $userId == $this->getAuth()->getIdentity()->id) {
            $user = Users\Table::findRow($userId);
            $user->email = $_COOKIE['new-email'];
            $user->save();
            setcookie('new-email', '', 0, '/');
            $token->delete();
            $this->getMessages()->addSuccess(__('Your email has been changed'));
        } else {
            $this->getMessages()->addError(__('Invalid token'));
        }
        $this->redirectTo('users', 'edit');
    }

    // for get info about user
    if ($request->getMethod() == AbstractRequest::METHOD_GET) {
        $request->data = array('id' => $this->getAuth()->getIdentity()->id);
    }

    $crud = new Users\CrudProfile();
    return $crud->processController();
};
