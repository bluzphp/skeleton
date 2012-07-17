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
namespace Bluz;

use Bluz\Acl\Acl;
use Bluz\Auth\Auth;
use Bluz\Cache\Cache;
use Bluz\Config\Config;
use Bluz\Db\Db;
use Bluz\EventManager\EventManager;
use Bluz\Messages\Messages;
use Bluz\Rcl\Rcl;
use Bluz\Registry\Registry;
use Bluz\Request;
use Bluz\Router\Router;
use Bluz\Session\Session;
use Bluz\View\Layout;
use Bluz\View\View;

/**
 * Application
 *
 * @category Bluz
 * @package  Application
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 16:25
 */
class Application
{
    use Singleton;

    /**
     * @var Acl
     */
    protected $acl;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Db
     */
    protected $db;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var Loader
     */
    protected $loader;

    /**
     * @var Layout
     */
    protected $layout;

    /**
     * @var Messages
     */
    protected $messages;

    /**
     * @var Rcl
     */
    protected $rcl;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Request\AbstractRequest
     */
    protected $request;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var string
     */
    protected $environment;

    /**
     * Use layout flag
     * @var boolean
     */
    protected $layoutFlag = true;

    /**
     * Use view flag
     * @var boolean
     */
    protected $viewFlag = true;

    /**
     * JSON response flag
     * @var boolean
     */
    protected $jsonFlag = false;

    /**
     * Widgets closures
     * @var array
     */
    protected $widgets = array();

    /**
     * init
     *
     * @param string $environment Array format only!
     * @throws Exception
     * @return Application
     */
    public function init($environment = ENVIRONMENT_PRODUCTION)
    {
        $this->environment = $environment;

        try {
            $this->getConfig($environment);
            $this->getLoader();

            $this->log(__METHOD__);

            $this->getCache();
            $this->getRegistry();
            $this->getSession();
            $this->getMessages();
            $this->getAuth();
            $this->getAcl();
            $this->getDb();
        } catch (Exception $e) {
            throw new Exception("Application can't be loaded: ". $e->getMessage());
        }
        return $this;
    }

    /**
     * log message, working with profiler
     *
     * @param  string $message
     * @return void
     */
    public function log($message)
    {
        $this->getEventManager()->trigger('log', $message);
    }

    /**
     * load config file
     *
     * @param string|null $environment
     * @return Config
     */
    public function getConfig($environment = null)
    {
        if (!$this->config) {
            $this->config = new Config();
            $this->config->load($environment);
        }
        return $this->config;
    }

    /**
     * config
     *
     * @param string|null $section of config
     * @param string|null $subsection of config
     * @return array
     */
    public function getConfigData($section = null, $subsection = null)
    {
        return $this->getConfig()->get($section, $subsection);
    }

    /**
     * getLoader
     *
     * @return Loader
     */
    public function getLoader()
    {
        if (!$this->loader) {
            $this->loader = new Loader();

            $conf = $this->getConfigData('loader');
            if (isset($conf['namespaces'])) {
                foreach ($conf['namespaces'] as $ns => $path) {
                    $this->loader -> registerNamespace($ns, $path);
                }
            }
            if (isset($conf['prefixes'])) {
                foreach ($conf['prefixes'] as $prefix => $path) {
                    $this->loader -> registerPrefix($prefix, $path);
                }
            }

            $this->loader -> register();
        }
        return $this->loader;
    }

    /**
     * getAcl
     *
     * @return Acl
     */
    public function getAcl()
    {
        if (!$this->acl) {
            $this->acl = new Acl();
        }
        return $this->acl;
    }

    /**
     * getAuth
     *
     * @return Auth
     */
    public function getAuth()
    {
        if (!$this->auth && $conf = $this->getConfigData('auth')) {
            $this->auth = new Auth($conf);
        }
        return $this->auth;
    }

    /**
     * getCache
     *
     * @return Cache
     */
    public function getCache()
    {
        if (!$this->cache) {
            $this->cache = new Cache($this->getConfigData('cache'));
        }
        return $this->cache;
    }

    /**
     * getDb
     *
     * @return Db
     */
    public function getDb()
    {
        if (!$this->db && $conf = $this->getConfigData('db')) {
            $this->db = new Db($conf);
        }
        return $this->db;
    }

    /**
     * getEventManager
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->eventManager = new EventManager();
        }
        return $this->eventManager;
    }

    /**
     * hasMessages
     *
     * @return boolean
     */
    public function hasMessages()
    {
        return ($this->messages != null);
    }

    /**
     * getMessages
     *
     * @return Messages
     */
    public function getMessages()
    {
        if (!$this->messages) {
            $this->messages = new Messages();
        }
        return $this->messages;
    }

    /**
     * getRcl
     *
     * @return Rcl
     */
    public function getRcl()
    {
        if (!$this->rcl) {
            $this->rcl = new Rcl();
        }
        return $this->rcl;
    }

    /**
     * getRegistry
     *
     * @return Registry
     */
    public function getRegistry()
    {
        if (!$this->registry && $conf = $this->getConfigData('registry')) {
            $this->registry = new Registry($conf);
        }
        return $this->registry;
    }

    /**
     * getRequest
     *
     * @return Request\HttpRequest|Request\CliRequest
     */
    public function getRequest()
    {
        if (!$this->request) {
            if ('cli' == PHP_SAPI) {
                $this->request = new Request\CliRequest($this->getConfigData('request'));
            } else {
                $this->request = new Request\HttpRequest($this->getConfigData('request'));
            }

            if ($this->request->isXmlHttpRequest()) {
                $this->useLayout(false);
            }
        }
        return $this->request;
    }

    /**
     * getRouter
     *
     * @return Router
     */
    public function getRouter()
    {
        if (!$this->router) {
            $this->router = new Router($this->getConfigData('router'));
        }
        return $this->router;
    }

    /**
     * getSession
     *
     * @return Session
     */
    public function getSession()
    {
        if (!$this->session) {
            $this->session = new Session($this->getConfigData('session'));
            $this->session->start();

            $this->getMessages();
        }
        return $this->session;
    }

    /**
     * getLayout
     *
     * @return Layout
     */
    public function getLayout()
    {
        if (!$this->layout && $conf = $this->getConfigData('layout')) {
            $this->layout = new Layout($conf);
        }
        return $this->layout;
    }

    /**
     * process
     *
     * @return Application
     */
    public function process()
    {
        $this->log(__METHOD__);

        $this->getRequest();

        $this->getRouter()
             ->process();

        if ($this->request->getParam('json')) {
            $this->useJson(true);
        }

        // TODO remove later
        if ($this->request->getParam('flushCache')) {
            $this->getCache()->handler()->flush();
        }

        $layout = $this->getLayout();
//        $layout->_code = 200;

        /* @var View $ControllerView */
        try {
            $dispatchResult = $this->dispatch(
                $this->request->module(),
                $this->request->controller(),
                $this->request->getParams()
            );

            // move vars from layout to view instance
            if ($dispatchResult instanceof View) {
                $dispatchResult -> setData($this->getLayout()->toArray());
            }

            $layout->setContent($dispatchResult);
        } catch (\Exception $e) {
            $dispatchResult = $this->dispatch('error', 'error', array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ));
            $layout->setContent($dispatchResult);
        }
        return $this;
    }

    /**
     * useLayout
     *
     * @param boolean|string $flag
     * @return Application
     */
    public function useLayout($flag = true)
    {
        if (is_string($flag)) {
            $this->getLayout()->setTemplate($flag);
            $this->layoutFlag = true;
        } else {
            $this->layoutFlag = $flag;
        }
        return $this;
    }

    /**
     * useView
     *
     * @param boolean|string $flag
     * @return Application
     */
    public function useView($flag = true)
    {
        if (!$flag) {
            // disable layout when disable view
            $this->useLayout(false);
        }
        $this->viewFlag = $flag;
        return $this;
    }

    /**
     * useJson
     *
     * @param boolean $flag
     * @return Application
     */
    public function useJson($flag = true)
    {
        if ($flag) {
            // disable view and layout for JSON output
            $this->useLayout(false);
            $this->useView(false);
        }
        $this->jsonFlag = $flag;
        return $this;
    }

    /**
     * render
     *
     * @return Application
     */
    public function render()
    {
        $this->log(__METHOD__);

        $layout = $this->getLayout();
        $content = $layout->getContent();

        $data = $layout->toArray();

        if ($this->hasMessages()) {
            $data['_messages'] = $this->getMessages()->popAll();
        }

        if (is_array($content)) {
            $data = array_merge($data, $content);
        }

        if ('cli' == PHP_SAPI) {
            foreach ($data as $key => $value) {
                if (strpos($key, '_') === 0) {
                    echo "\033[1;31m$key\033[m:\n";
                } else {
                    echo "\033[1;33m$key\033[m:\n";
                }
                var_dump($value);
                echo "\n";
            }
        } else {
            if ($this->jsonFlag) {
                header('Content-type: application/json', true, 200); //override response code so javascript can process it
                echo json_encode($data);
            } elseif (!$this->layoutFlag) {
                echo ($content instanceof \Closure) ? $content(): $content;
            } else {
                echo $layout;
            }
        }
        return $this;
    }

    /**
     * reflection for anonymous function
     *
     * @param string  $file
     * @return array
     */
    public function reflection($file)
    {
        // cache for reflection data
        if (!$data = $this->getCache()->get('Reflection: '.$file)) {

            // TODO: workaround for get reflection of closure function
            $bootstrap = $request = $identity = $view = null;
            $closure = include $file;

            $reflection = new \ReflectionFunction($closure);

            // check and normalize params by doc comment
            $docComment = $reflection->getDocComment();
            preg_match_all('/\s*\*\s*\@param\s+(bool|boolean|int|integer|float|string|array)\s+\$([a-z0-9_]+)/i', $docComment, $matches);

            // init data
            $data = array();

            // rebuild array
            $data['types'] = array();
            foreach ($matches[1] as $i => $type) {
                $data['types'][$matches[2][$i]] = $type;
            }

            $data['params'] = $reflection->getParameters();

            // check cache settings
            if (preg_match('/\s*\*\s*\@cache\s+([0-9\.]+).*/i', $docComment, $matches)) {
                $data['cache'] = (int) $matches[1];
            }
            // check routers
            if (preg_match('/\s*\*\s*\@route\s+(.*)\s*/i', $docComment, $matches)) {
                $data['route'] = $matches[1];
            }
            // check acl settings
            if (preg_match('/\s*\*\s*\@privilege\s+(\w+).*/i', $docComment, $matches)) {
                $data['privilege'] = $matches[1];
            }
            // check rcl settings
            if (preg_match('/\s*\*\s*\@resource\s+(\w+)(\s\w+|).*/i', $docComment, $matches)) {
                $data['resourceType'] = $matches[1];
                $data['resourceParam'] = trim($matches[2]);
            }

            $this->getCache()->set('Reflection: '.$file, $data);
        }
        return $data;
    }

    /**
     * process params
     *
     * @param array $reflectionData
     * @return array
     */
    private function params($reflectionData)
    {
        $request = $this->getRequest();
        $params = array();
        foreach ($reflectionData['params'] as $param) {
            /* @var \ReflectionParameter $param */
            if (isset($reflectionData['types'][$param->name])
                && $type = $reflectionData['types'][$param->name]) {
                switch ($type) {
                    case 'bool':
                    case 'boolean':
                        $params[] = (bool) $request->{$param->name};
                        break;
                    case 'int':
                    case 'integer':
                        $params[] = (int) $request->{$param->name};
                        break;
                    case 'float':
                        $params[] = (float) $request->{$param->name};
                        break;
                    case 'string':
                        $params[] = (string) $request->{$param->name};
                        break;
                    case 'array':
                        $params[] = (array) $request->{$param->name};
                        break;
                }
            } else {
                $params[] = $request->{$param->name};
            }
        }
        return $params;
    }

    /**
     * dispatch
     *
     * Call dispatch from any \Bluz\Package
     * <code>
     * $this->getApplication()->dispatch($module, $controller, array $params);
     * </code>
     *
     * Attach callback function to event "dispatch"
     * <code>
     * $this->getApplication()->getEventManager()->attach('dispatch', function($event) {
     *     $eventParams = $event->getParams();
     *     $app = $event->getTarget();
     *     \Bluz\Profiler::log('bootstrap:dispatch: '.$eventParams['module'].'/'.$eventParams['controller']);
     * });
     * </code>
     *
     * @param string $module
     * @param string $controller
     * @param array  $params
     * @throws Exception
     * @return View
     */
    public function dispatch($module, $controller, $params = array())
    {
        $this->log(__METHOD__.": ".$module.'/'.$controller);
        $controllerFile = $this->getControllerFile($module, $controller);
        $reflectionData = $this->reflection($controllerFile);

        // system trigger "dispatch"
        $this->getEventManager()->trigger('dispatch', $this, array(
            'module' => $module,
            'controller' => $controller,
            'params' => $params,
            'reflection' => $reflectionData
        ));

        $identity = $this->getAuth()->getIdentity();

        // check acl
        if (!$this->isAllowedController($module, $controller, $params)) {
            if (!$identity) {
                $this->redirectTo('users', 'login');
            }
            throw new Exception('You don\'t have permissions', 403);
        }

        // $request for use in closure
        $request = $this->getRequest();
        $request -> setParams($params);

        // $view for use in closure
        $view = new View($this->getConfigData('view'));
        $view -> setPath(PATH_APPLICATION .'/modules/'. $module .'/views');
        $view -> setTemplate($controller .'.phtml');

        $bootstrapPath = PATH_APPLICATION .'/modules/' . $module .'/bootstrap.php';

        /**
         * optional $bootstrap for use in closure
         * @var \closure $bootstrap
         */
        if (file_exists($bootstrapPath)) {
            $bootstrap = require $bootstrapPath;
        } else {
            $bootstrap = null;
        }

        /**
         * @var \closure $controllerClosure
         */
        $controllerClosure = include $controllerFile;

        if (!is_callable($controllerClosure)) {
            throw new Exception("Controller is not callable '$module/$controller'");
        }

        $params = $this->params($reflectionData);

        // load html from cache file
        if (isset($reflectionData['cache'])) {
            if ($view->cache($reflectionData['cache'], $params)) {
                return $view;
            }
        };

        $result = call_user_func_array($controllerClosure, $params);

        // return false is equal to disable view and layout
        if ($result === false) {
            $this->useLayout(false);
            $this->useView(false);
            return false;
        }

        // return array is equal to use json
        if (is_array($result)) {
            $this->useJson(true);
            return $result;
        }

        // return closure is replace logic of controller
        if (is_callable($result)) {
            return $result;
        }

        return $view;
    }

    /**
     * widget
     *
     * Call widget from any \Bluz\Package
     * <code>
     * $this->getApplication()->widget($module, $widget, array $params);
     * </code>
     *
     * Attach callback function to event "widget"
     * <code>
     * $this->getApplication()->getEventManager()->attach('widget', function($event) {
     *     $eventParams = $event->getParams();
     *     $app = $event->getTarget();
     *     \Bluz\Profiler::log('bootstrap:dispatch: '.$eventParams['module'].'/'.$eventParams['widget']);
     * });
     * </code>
     *
     * @param string $module
     * @param string $widget
     * @param array  $params
     * @throws Exception
     * @return \Closure
     */
    public function widget($module, $widget, $params = array())
    {
        $this->log(__METHOD__.": ".$module.'/'.$widget);
        $controllerFile = $this->getWidgetFile($module, $widget);
        $reflectionData = $this->reflection($controllerFile);

        $this->getEventManager()->trigger('widget', $this, array(
            'module' => $module,
            'widget' => $widget,
            'params' => $params,
            'reflection' => $reflectionData
        ));

        /**
         * Cachable widgets
         * @var \Closure $widgetClosure
         */
        if (isset($this->widgets[$module])
            && isset($this->widgets[$module][$widget])) {
            $widgetClosure = $this->widgets[$module][$widget];
        } else {
            $widgetClosure = require $this->getWidgetFile($module, $widget);

            if (!isset($this->widgets[$module])) {
                $this->widgets[$module] = array();
            }
            $this->widgets[$module][$widget] = $widgetClosure;
        }

        if (!is_callable($widgetClosure)) {
            throw new Exception("Widget is not callable '$module/$widget'");
        }

        return $widgetClosure;
    }

    /**
     * Is allowed controller
     *
     * @param string $module
     * @param string $controller
     * @param array  $params
     * @return boolean
     */
    public function isAllowedController($module, $controller, array $params = array())
    {
        $controllerFile = $this->getControllerFile($module, $controller);

        $data = $this->reflection($controllerFile);

        if (isset($data['privilege']) &&
            !$this->getAcl()->isAllowed($module, $data['privilege'])) {
            // privilege is described and deny
            return false;
        }

        if (isset($data['privilege']) && isset($data['resourceType'])) {
            $resourceId = null;
            if (!empty($data['resourceParam'])) {
                $resourceId = isset($params[$data['resourceParam']]) ? $params[$data['resourceParam']] : null;
            }
            return $this->getRcl()->isAllowed($module, $data['privilege'], $data['resourceType'], $resourceId);
        }

        return true;
    }

    /**
     * Is allowed widget
     *
     * @param string $module
     * @param string $widget
     * @param array  $params
     * @return boolean
     */
    public function isAllowedWidget($module, $widget, array $params = array())
    {
        $widgetFile = $this->getWidgetFile($module, $widget);

        $data = $this->reflection($widgetFile);

        if (isset($data['privilege']) &&
            !$this->getAcl()->isAllowed($module, $data['privilege'])) {
            // privilege is described and deny
            return false;
        }

        if (isset($data['privilege']) && isset($data['resourceType'])) {
            $resourceId = null;
            if (!empty($data['resourceParam'])) {
                $resourceId = isset($params[$data['resourceParam']]) ? $params[$data['resourceParam']] : null;
            }
            return $this->getRcl()->isAllowed($data['privilege'], $data['resourceType'], $resourceId);
        }

        return true;
    }

    /**
     * Get controller file
     *
     * @param  string $module
     * @param  string $controller
     * @return \Closure
     * @throws Exception
     */
    public function getControllerFile($module, $controller)
    {
        $controllerPath = PATH_APPLICATION . '/modules/' . $module
                        .'/controllers/' . $controller .'.php';

        if (!file_exists($controllerPath)) {
            throw new Exception("Controller not found '$module/$controller'", 404);
        }

        return $controllerPath;
    }

    /**
     * Get widget file
     *
     * @param  string $module
     * @param  string $widget
     * @return \Closure
     * @throws Exception
     */
    public function getWidgetFile($module, $widget)
    {
        $widgetPath = PATH_APPLICATION . '/modules/' . $module
                        .'/widgets/' . $widget .'.php';

        if (!file_exists($widgetPath)) {
            throw new Exception("Widget not found '$module/$widget'");
        }

        return $widgetPath;
    }

    /**
     * reload
     * please, be careful to avoid loop of reload
     *
     * @throws Exception
     * @return void
     */
    public function reload()
    {
        // for AJAX controllers
        if ($this->jsonFlag) {
            $this->getLayout()->_reload = true;
            return;
        }

        // for other controllers
        if (!headers_sent($file, $line)) {
            // save notification to session
            // if they exists
            header('Refresh: 15; url=' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            throw new Exception("Headers already sent by $file:$line", 503);
        }
    }

    /**
     * redirect
     *
     * @param string $url
     * @throws Exception
     * @return void
     */
    public function redirect($url)
    {
        // for AJAX controllers
        if ($this->jsonFlag) {
            $this->getLayout()->_redirect = $url;
            return;
        }

        // for other controllers
        if (!headers_sent($file, $line)) {
            // save notification to session
            // if they exists
            header('Location: '.$url);
            exit;
        } else {
            throw new Exception("Headers already sent by $file:$line", 503);
        }
    }

    /**
     * redirect
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return void
     */
    public function redirectTo($module = 'index', $controller = 'index', $params = array())
    {
        $url = $this->getRouter()->url($module, $controller, $params);
        $this->redirect($url);
    }
}
