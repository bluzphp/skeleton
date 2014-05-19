<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Application\Application;
use Bluz\Application\Exception\ForbiddenException;

/**
 * Bootstrap
 *
 * @category Application
 * @package  Bootstrap
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 17:38
 */
class Bootstrap extends Application
{
    /**
     * initial environment
     *
     * @param string $environment
     * @return self
     */
    public function init($environment = 'production')
    {
        $res = parent::init($environment);

        // dispatch hook for acl realization
        $this->getEventManager()->attach(
            'dispatch',
            function ($event) {
                /* @var \Bluz\EventManager\Event $event */
                $eventParams = $event->getParams();
                $this->log('bootstrap:dispatch: '.$eventParams['module'].'/'.$eventParams['controller']);
            }
        );

        // widget hook for acl realization
        $this->getEventManager()->attach(
            'widget',
            function ($event) {
                /* @var \Bluz\EventManager\Event $event */
                $eventParams = $event->getParams();
                $this->log('bootstrap:widget: '.$eventParams['module'].'/'.$eventParams['widget']);
            }
        );


        // example of setup Layout
        $this->getLayout()->title("Bluz Skeleton");

        return $res;
    }

    /**
     * denied
     *
     * @throws ForbiddenException
     * @return void
     */
    public function denied()
    {
        // process AJAX request
        if (!$this->getRequest()->isXmlHttpRequest()) {
            $this->getMessages()->addError('You don\'t have permissions, please sign in');
        }
        // redirect to login page
        if (!$this->user()) {
            // save URL to session
            $this->getSession()->rollback = $this->getRequest()->getRequestUri();
            $this->redirectTo('users', 'signin');
        }
        throw new ForbiddenException();
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        if ($this->debugFlag && !headers_sent()) {
            $debugString = sprintf(
                "%f; %skb",
                microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
                ceil((memory_get_usage()/1024))
            );
            $debugString .= '; '.$this->getRequest()->getModule() .'/'. $this->getRequest()->getController();

            $this->getResponse()->setHeader('Bluz-Debug', $debugString);

            $debugBar = json_encode($this->getLogger()->get('info'));

            $this->getResponse()->setHeader('Bluz-Bar', $debugBar);
        }
        parent::render();
    }

    /**
     * @return Application
     */
    public function finish()
    {
        if ($messages = $this->getLogger()->get('error')) {
            errorLog(join("\n", $messages)."\n");
        }
        return $this;
    }
}
