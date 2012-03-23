<?php
/**
 * Test of events
 * 
 * @author   Anton Shevchuk
 * @created  16.03.12 15:21
 * @return closure
 */
namespace Bluz;
use \Bluz\EventManager\Event;
return
/**
 * @return closure
 */
function() use ($app) {
    /* @var Application $app */
    $app->getEventManager()
        ->attach('testevent', function(Event $event){
            return $event->getTarget()*2;
        })
        ->attach('testevent', function(Event $event){
            return $event->getTarget()*2;
        });

    $app->getEventManager()->attach('testspace:event', function(Event $event){
        return $event->getTarget()+4;
    });
    $app->getEventManager()->attach('testspace:event', function(Event $event){
        return $event->getTarget()+2;
    });

    $app->getEventManager()->attach('testspace:event2', function(Event $event){
        $event->setTarget($event->getTarget()+5);
        return false;
    });
    $app->getEventManager()->attach('testspace:event2', function(Event $event){
        echo "Never run";
    });
    $app->getEventManager()->attach('testspace', function(Event $event){
        return $event->getTarget()+1;
    });


    $res1 = $app->getEventManager()->trigger('testevent', 1, array(1,2,3));
    $res2 = $app->getEventManager()->trigger('testspace:event', 1, array(1,2,3));
    $res3 = $app->getEventManager()->trigger('testspace:event2', 1, array(1,2,3));

    var_dump($res1, $res2, $res3);
    return false;
};