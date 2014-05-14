<?php
/**
 * Test of events
 * 
 * @author   Anton Shevchuk
 * @created  16.03.12 15:21
 * @return closure
 */
namespace Application;

use Bluz;
use Bluz\EventManager\Event;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $view->ahref('Test', ['test', 'index']),
            'Events',
        ]
    );

    $this->getEventManager()
        ->attach(
            'testevent',
            function (Event $event) {
                return $event->getTarget()*2;
            }
        )
        ->attach(
            'testevent',
            function (Event $event) {
                return $event->getTarget()*2;
            }
        );

    $this->getEventManager()->attach(
        'testspace:event',
        function (Event $event) {
            return $event->getTarget()+4;
        }
    );

    $this->getEventManager()->attach(
        'testspace:event',
        function (Event $event) {
            return $event->getTarget()+2;
        }
    );

    $this->getEventManager()->attach(
        'testspace:event2',
        function (Event $event) {
            $event->setTarget($event->getTarget()+5);
            return false;
        }
    );

    $this->getEventManager()->attach(
        'testspace:event2',
        function (Event $event) {
            echo "Never run". $event->getName();
        }
    );

    $this->getEventManager()->attach(
        'testspace',
        function (Event $event) {
            return $event->getTarget()+1;
        }
    );

    $view->res1 = $this->getEventManager()->trigger('testevent', 1);
    $view->res2 = $this->getEventManager()->trigger('testspace:event', 1);
    $view->res3 = $this->getEventManager()->trigger('testspace:event2', 1);
};
