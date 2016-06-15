<?php
/**
 * Test of events
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  16.03.12 15:21
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\EventManager\Event;
use Bluz\Proxy\EventManager;
use Bluz\Proxy\Layout;

/**
 * @return array
 */
return function () {
    /**
     * @var Controller $this
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

    return [
        'res1' => EventManager::trigger('testevent', 1),
        'res2' => EventManager::trigger('testspace:event', 1),
        'res3' => EventManager::trigger('testspace:event2', 1),
    ];
};
