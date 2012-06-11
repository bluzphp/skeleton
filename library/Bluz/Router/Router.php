<?php

/**
 * Copyright (c) 2012 by Bluz PHP Team
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
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Bluz\Router;

/**
 * Router
 *
 * @category Bluz
 * @package  Router
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:16
 */
class Router
{
    use \Bluz\Package;

    const DEFAULT_MODULE = 'index';
    const DEFAULT_CONTROLLER = 'index';

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var array
     */
    protected $reverse = array();

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * getBaseUrl
     * always return string with slash at end
     * @return string
     */
    public function getBaseUrl()
    {
        if (!$this->baseUrl) {
            $this->baseUrl = $this->getApplication()
                ->getRequest()
                ->getBaseUrl()
            ;
        }
        return $this->baseUrl;
    }

    /**
     * build URL
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return string
     */
    public function url($module = null, $controller = null, $params = null)
    {
        if (empty($this->routes)) {
            return $this->defaultUrl($module, $controller, $params);
        } else {
            /**
             * $reverse = array(
             *     'module1' => array(
             *          'controller1' => array('params'=>array(), 'rule'=>index1),
             *          'controller2' => array('params'=>array(), 'rule'=>index2),
             *      )
             * )
             *
             * $routers = array(
             *     '/users/{controller}/{id}' => array('users', 'controller', 'params' => array('id')),
             *     '/{alias}.html' => array('pages', 'view', 'params' => array('alias')),
             *
             * )
             */
            return $this->defaultUrl($module, $controller, $params);
        }
    }


    /**
     * build URL by default route
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return string
     */
    public function defaultUrl($module = null, $controller = null, $params = null)
    {
        if (null === $module) {
            $module = self::DEFAULT_MODULE;
        }

        if (null === $controller) {
            $controller = self::DEFAULT_CONTROLLER;
        }

        if (null === $params) {
            $params = array();
        }

        $url = $this->getBaseUrl();

        if (empty($params)) {
            if ($controller == 'index') {
                if ($module == 'index') {
                    return $url;
                } else {
                    return $url . $module;
                }
            }
        }

        $url .= $module.'/'.$controller;
        $postfix = '';
        foreach ($params as $key => $value) {
            if (empty($key)) {
                $postfix .= $value;
            } else if (!empty($value) || $value === "0") {
                $url .= '/'.urlencode($key).'/'.urlencode($value);
            }
        }
        return $url . $postfix;
    }

    /**
     * process
     *
     * @return \Bluz\Request\AbstractRequest
     */
    public function process()
    {
        $request = $this->getApplication()->getRequest();

        if (sizeof($this->routes)) {
            $request = $this->processRoute($request);
        } else {
            $request = $this->processDefault($request);
        }

        return $request;
    }

    /**
     * process default router
     *
     * @param  \Bluz\Request\AbstractRequest $request
     * @return \Bluz\Request\AbstractRequest
     */
    protected function processDefault($request)
    {
        $uri = parse_url($request->getRequestUri());
        $uri = $uri['path'];

        if ($this->getBaseUrl() && strpos($uri, $this->getBaseUrl()) === 0) {
            $uri = substr($uri, strlen($this->getBaseUrl()));
        }

        $uri = trim($uri, '/');
        if (empty($uri)) return $request;

        $params = preg_split('/\//', $uri);

        if (sizeof($params)) {
            $request->module(array_shift($params));
        }
        if (sizeof($params)) {
            $request->controller(array_shift($params));
        }

        if ($size = sizeof($params)) {
            if ($size%2==1) {
                array_pop($params);
                $size = sizeof($params);
            }
            // or use array_chunk and run another loop?
            for ($i = 0; $i < $size; $i=$i+2) {
                $request->{$params[$i]} = $params[$i+1];
            }
        }

        return $request;
    }

    /**
     * process custom router
     *
     * @param  \Bluz\Request\AbstractRequest $request
     * @return \Bluz\Request\AbstractRequest
     */
    protected function processRoute($request)
    {
        // TODO Check this
        $params = array();

        foreach ($params as $param => $value) {
            if ($param === 'module') {
                $request->module($value);
            } elseif ($param === 'controller') {
                $request->controller($value);
            } else {
                $request->{$param} = $value;
            }
        }
        return $request;
    }
}
