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
use Bluz\Auth\AuthException;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Response;
use Bluz\Proxy\Request;
use Bluz\Proxy\Session;

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
     * {@inheritdoc}
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return void
     */
    protected function preDispatch($module, $controller, $params = array())
    {
        // example of setup default title
        Layout::title("Bluz Skeleton");
        // remember me
        if (!$this->user() && !empty($_COOKIE['rToken']) && !empty($_COOKIE['rId'])) {
            // try to login
            try {
                Auth\Table::getInstance()->authenticateCookie($_COOKIE['rId'], $_COOKIE['rToken']);
            } catch (AuthException $e) {
                $this->getResponse()->setCookie('rId', '', 1, '/');
                $this->getResponse()->setCookie('rToken', '', 1, '/');
            }
        }

        parent::preDispatch($module, $controller, $params);
    }
    /**
     * {@inheritdoc}
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return void
     */
    protected function postDispatch($module, $controller, $params = array())
    {
        parent::postDispatch($module, $controller, $params);
    }

    /**
     * Denied access
     * @throws ForbiddenException
     * @return void
     */
    public function denied()
    {
        // process AJAX request
        if (!Request::isXmlHttpRequest()) {
            Messages::addError('You don\'t have permissions, please sign in');
        }
        // redirect to login page
        if (!$this->user()) {
            // save URL to session
            Session::set('rollback', Request::getRequestUri());
            $this->redirectTo('users', 'signin');
        }
        throw new ForbiddenException();
    }

    /**
     * Render with debug headers
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
            $debugString .= '; '.Request::getModule() .'/'. Request::getController();

            Response::setHeader('Bluz-Debug', $debugString);

            $debugBar = json_encode(Logger::get('info'));

            Response::setHeader('Bluz-Bar', $debugBar);
        }
        parent::render();
    }

    /**
     * Finish it
     * @return void
     */
    public function finish()
    {
        if ($messages = Logger::get('error')) {
            foreach ($messages as $message) {
                errorLog($message);
            }
        }
    }
}
