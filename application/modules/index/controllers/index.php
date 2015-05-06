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

 /*   try{
        $hybridauth = new \Hybrid_Auth( Config::getData('hybridauth') );

        $twitter = $hybridauth->authenticate( "Facebook" );

        $user_profile = $twitter->getUserProfile();

        echo "Hi there! " . $user_profile->displayName;

        $twitter->setUserStatus( "Hello world!" );

        $user_contacts = $twitter->getUserContacts();
        $hybridauth->logoutAllProviders();
    }
    catch( Exception $e ){
        echo "Ooophs, we got an error: " . $e->getMessage();
    }*/

};
