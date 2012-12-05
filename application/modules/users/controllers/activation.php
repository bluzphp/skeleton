<?php
/**
 * User Activation
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  05.12.12 15:17
 */
namespace Application;

return
    /**
     *
     * @param int $id User UID
     * @param string $code
     * @return \closure
     */
    function ($id, $code) {
        /**
         * @var \Bluz\Application $this
         */



        $this->redirectTo('users', 'login');
        return false;
    };