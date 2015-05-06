<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 05.05.15
 * Time: 17:30
 */

namespace Application;

use Application\Auth\AuthFactory;
use Application\Users;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

return
    /**
     * @param int $id User UID
     * @param string $code
     * @return \closure
     */
    function () {

        /**
         * @var Bootstrap $this
         */
        try{
            $provider = Request::getParam('provider');
            $auth = new AuthFactory();
            $auth->setProvider($provider);
            $auth->setResponse($this);
            $auth->setIdentity($this->user());
            $auth->authProcess();
        }catch (Exception $e) {
            Messages::addError($e->getMessage());
        }
        return
            function () {
                return false;
            };


     //   $config = Config::getData('hybridauth');

        try{
        $hybridauth = new \Hybrid_Auth( $config );

        $twitter = $hybridauth->authenticate( "Facebook" );

        if ($twitter->isUserConnected()) {
        $user_profile = $twitter->getUserProfile();
        echo "Hi there! " . $user_profile->displayName;
        $twitter->logout();
        }
        }
        catch( Exception $e ){
        echo "Ooophs, we got an error: " . $e->getMessage();
        }

    };
