<?php
/**
 * Default module/controller
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:39
 * @return   \Closure
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Proxy\Config;

return
/**
 * @var Bootstrap $this
 * @return void
 */
function () {

    $config = Config::getData('hybridauth');

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
