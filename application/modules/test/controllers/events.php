<?php
/**
 * Test of events
 *
 * @author   Anton Shevchuk
 * @created  16.03.12 15:21
 * @return closure
 */
namespace Application;

use Bluz\EventManager\Event;
use Bluz\Proxy\EventManager;
use Bluz\Proxy\Layout;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Test', ['test', 'index']),
            'Events',
        ]
    );

    EventManager::attach(
        'testevent',
        function (Event $event) {
            return $event->getTarget()*2;
        }
    );

    EventManager::attach(
        'testevent',
        function (Event $event) {
            return $event->getTarget()*2;
        }
    );

    EventManager::attach(
        'testspace:event',
        function (Event $event) {
            return $event->getTarget()+4;
        }
    );

    EventManager::attach(
        'testspace:event',
        function (Event $event) {
            return $event->getTarget()+2;
        }
    );

    EventManager::attach(
        'testspace:event2',
        function (Event $event) {
            $event->setTarget($event->getTarget()+5);
            return false;
        }
    );

    EventManager::attach(
        'testspace:event2',
        function (Event $event) {
            echo "Never run". $event->getName();
        }
    );

    EventManager::attach(
        'testspace',
        function (Event $event) {
            return $event->getTarget()+1;
        }
    );

    $view->res1 = EventManager::trigger('testevent', 1);
    $view->res2 = EventManager::trigger('testspace:event', 1);
    $view->res3 = EventManager::trigger('testspace:event2', 1);
};
