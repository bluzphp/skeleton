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
namespace Bluz\View;

use Bluz\Application;

/**
 * View
 *
 * @category Bluz
 * @package  View
 *
 * @author   Anton Shevchuk, ErgallM
 * @created  08.07.11 11:49
 */
class View
{
    use \Bluz\Package;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected static $viewHelpers = array();

    /**
     * @var string
     */
    protected static $viewHelpersPath = array();

    /**
     * @var mixed
     */
    protected $content;

    /**
     * @var string path to template
     */
    protected $path;

    /**
     * Global cache flag
     *
     * @var boolean
     */
    protected $cache = false;

    /**
     * @var string
     */
    protected $cachePath;

    /**
     * @var string
     */
    protected $cacheFile;

    /**
     * @var boolean
     */
    protected $cacheFlag = false;

    /**
     * @var string
     */
    protected $template;

    /**
     * init
     *
     * @param null|array    $options
     * @return void
     */
    public function init($options = null)
    {
        // initial default helper path
        $this->addViewHelperPath(dirname(__FILE__) . '/Helper/');
    }

    /**
     * Get a variable
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return null;
        }
    }


    /**
     * Is set a variable
     *
     * @param string $key
     * @return mixed
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Assign a variable
     *
     * A $value of null will unset the $key if it exists
     *
     * @param string $key
     * @param mixed $value
     * @return View
     */
    public function __set($key, $value)
    {
        $key = (string) $key;

        if ((null === $value) && isset($this->data[$key])) {
            unset($this->data[$key]);
        } elseif (null !== $value) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Call
     *
     * @param string $method
     * @param array  $args
     * @throws ViewException
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (isset(self::$viewHelpers[$method])
            && self::$viewHelpers[$method] instanceof \Closure) {
            return call_user_func_array(self::$viewHelpers[$method], $args);
        }
        if (self::$viewHelpersPath) {
            foreach(self::$viewHelpersPath as $helperPath) {
                $helperPath = realpath($helperPath . '/' . ucfirst($method) . '.php');
                if ($helperPath) {
                    $helperInclude = include $helperPath;
                    if ($helperInclude instanceof \Closure) {
                        self::$viewHelpers[strtolower($method)] = $helperInclude;
                    } else {
                        throw new ViewException("View helper '$method' not found");
                    }

                    return $this->__call($method, $args);
                }
            }
        }
    }

    /**
     * Translate
     *
     * @param string $message
     * @return string
     */
    public function __($message)
    {
        $message = (string) $message;

        if (func_num_args() > 1) {
            $args = func_get_args();
            $args['0'] = $message; // substitute message
            $message = call_user_func_array('sprintf', $args);
        }
        return $message;
    }

    /**
     * set data from array
     *
     * @param array $data
     * @return View
     */
    public function setData($data = array())
    {
        $this->data = $this->mergeArrays($this->data, $data);
        return $this;
    }

    /**
     * @param $array1
     * @param $array2
     * @return array
     */
    protected function mergeArrays($array1, $array2)
    {
        foreach ($array2 as $key => $value) {
            if (array_key_exists($key, $array1) && is_array($value)) {
                $array1[$key] = $this->mergeArrays($array1[$key], $array2[$key]);
            } else {
                $array1[$key] = $value;
            }
        }
        return $array1;
    }

    /**
     * is callable
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->render();
    }

    /**
     * render like string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * setCache
     *
     * @param boolean $flag
     * @return View
     */
    public function setCache($flag)
    {
        $this->cache = (boolean) $flag;
        return $this;
    }

    /**
     * setCachePath
     *
     * @param string $path
     * @throws ViewException
     * @return View
     */
    public function setCachePath($path)
    {
        if (!is_dir($path) or !is_writable($path)) {
            throw new ViewException('View: Cache path is not writable');
        }
        $this->cachePath = $path;
        return $this;
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        $data = array();
        foreach ($this->data as $key => $value) {
            if ($value instanceof self) {
                $value = $value->toArray();
            }
            $data[$key] = $value;
        }
        return $data;
    }

    /**
     * setup path
     *
     * @param string $path
     * @return View
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Set view helpers
     *
     * @param array $viewHelpers
     * @return View
     */
    public function setViewHelpers($viewHelpers)
    {
        foreach ($viewHelpers as $name => $function) {
            self::$viewHelpers[$name] = $function;
        }
        return $this;
    }

    /**
     * Set view helpers path
     *
     * @param string|array $viewHelpersPath
     * @return View
     */
    public function setViewHelpersPath($viewHelpersPath)
    {
        if (is_array($viewHelpersPath)) {
            foreach ($viewHelpersPath as $path) {
                $this->addViewHelperPath((string) $path);
            }
        } else {
            $this->addViewHelperPath((string) $viewHelpersPath);
        }
        return $this;
    }

    /**
     * Add view helper path
     *
     * @param string $path
     * @return View
     */
    public function addViewHelperPath($path)
    {
        $path = rtrim(realpath($path), '/');
        if (false !== $path && !in_array($path, self::$viewHelpersPath)) {
            self::$viewHelpersPath[] = $path;
        }

        return $this;
    }

    /**
     * setup template
     *
     * @param string $file
     * @return View
     */
    public function setTemplate($file)
    {
        $this->template = $file;
        return $this;
    }

    /**
     * Get identity
     *
     * @return \Bluz\Auth\AbstractEntity
     */
    public function getIdentity()
    {
        return $this->getApplication()->getAuth()->getIdentity();
    }

    /**
     * A href url
     *
     * @param string                 $name
     * @param string                 $module
     * @param string                 $controller
     * @param array                  $params
     * @param array                  $attributes
     * @param \Bluz\View\boolean|bool $hideDenied
     * @return string
     */
    public function ahref($name, $module = 'index', $controller = 'index', $params = array(), $attributes = array(), $hideDenied = true)
    {
        if (!$this->getApplication()->isAllowedController($module, $controller, $params)) {
            return $hideDenied ? '' : $name;
        }

        $href = $this->url($module, $controller, $params);

        if ($href == $this->getApplication()->getRequest()->getRequestUri()) {
            if (isset($attributes['class'])) {
                $attributes['class'] .= ' on';
            } else {
                $attributes['class'] = 'on';
            }
        }
        $attrs = array();

        foreach ($attributes as $attr => $value) {
            $attrs[] = $attr .'="'.$value.'"';
        }

        return '<a href="'.$href.'" '.join(' ', $attrs).'>'.$this->__($name).'</a>';
    }

    /**
     * baseUrl
     *
     * TODO: realization
     * @param string $file
     * @return string
     */
    public function baseUrl($file)
    {
        // setup baseUrl
        if (!$this->baseUrl) {
            $this->baseUrl = $this->getApplication()
                ->getRequest()
                ->getBaseUrl()
            ;
            // clean script name
            if (isset($_SERVER['SCRIPT_NAME'])
                && ($pos = strripos($this->baseUrl, basename($_SERVER['SCRIPT_NAME']))) !== false) {
                $this->baseUrl = substr($this->baseUrl, 0, $pos);
            }
        }

        // Remove trailing slashes
        if (null !== $file) {
            $file = ltrim($file, '/\\');
        }

        return rtrim($this->baseUrl, '/') .'/'. $file;
    }

    /**
     * dispatch
     *
     * <code>
     * $this->dispatch($module, $controller, array $params);
     * </code>
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return View
     */
    public function dispatch($module, $controller, $params = array())
    {
        $application = $this->getApplication();
        try {
            $view = $application->dispatch($module, $controller, $params);

            if ($view instanceof \Closure) {
                return $view();
            }
            return $view;
        } catch (\Bluz\Acl\AclException $e) {
            // nothing for Acl exception
        }
    }

    /**
     * widget
     *
     * <code>
     * $this->widget($module, $controller, array $params);
     * </code>
     *
     * @param string $module
     * @param string $widget
     * @param array $params
     * @return View
     */
    public function widget($module, $widget, $params = array())
    {
        $application = $this->getApplication();
        try {
            $widgetClosure = $application->widget($module, $widget);
            call_user_func_array($widgetClosure, $params);
        } catch (\Bluz\Acl\AclException $e) {
            // nothing for Acl exception
        }
    }

    /**
     * start cache
     *
     * @param integer $ttl in minutes
     * @param array $keys
     * @return View
     */
    public function cache($ttl, $keys = array())
    {
        if (!$this->cache) {
            return false;
        }

        $cachePath = substr($this->path, strrpos($this->path, '/', -7) + 1);
        $cachePath = substr($cachePath, 0, -5);
        $cachePath = $this->cachePath .'/'. $cachePath . substr($this->template, 0, -6);

        $key = md5(serialize($keys)) .'.html';

        $this->cacheFile = $cachePath.'/'.$key;

        if (is_file($this->cacheFile) && (filemtime($this->cacheFile) > (time() - $ttl*60))) {
            $this->cacheFlag = true;
            return true;
        } else {
            // create new cache
            // clean old cache
            if (is_file($this->cacheFile)) {
                @unlink($this->cacheFile);
            }
            return false;
        }
    }

    /**
     * @return void
     */
    private function createCacheDir()
    {
        $path = substr($this->cacheFile, 0, strrpos($this->cacheFile, '/')) .'/';

        // use or create path recursive
        is_dir($path) || mkdir($path, 0755, true);
        @chmod($path, 0755);
    }

    /**
     * @param $content
     * @return void
     */
    private function writeCacheFile($content)
    {
        $this->createCacheDir();
        file_put_contents($this->cacheFile, $content);
        @chmod($this->cacheFile, 0755);
    }

    /**
     * Render
     *
     * @throws ViewException
     * @return string
     */
    public function render()
    {
        // cache exists and valid
        if ($this->cacheFlag) {
            return file_get_contents($this->cacheFile);
        }

        ob_start();
        try {
            if (!file_exists($this->path .'/'. $this->template)) {
                throw new ViewException("Template '{$this->template}' not found");
            }

            extract($this->data);
            require $this->path .'/'.  $this->template;
        } catch (\Exception $e) {
            echo $e->getMessage();
            var_dump($e->getTraceAsString());
        }

        $content = ob_get_clean();
        // save cache
        if ($this->cache && $this->cacheFile) {
            $this->writeCacheFile($content);
        }
        return (string) $content;
    }

    /**
     * partial
     *
     * be careful, method rewrites the View variables with params
     *
     * @param       $__template
     * @param array $__params
     * @throws ViewException
     * @return string
     */
    public function partial($__template, $__params = array())
    {
        if (!file_exists($this->path .'/'. $__template)) {
            throw new ViewException("Template '{$__template}' not found");
        }

        extract($this->data);

        if (sizeof($__params)) {
            extract($__params);
        }
        unset($__params);

        require $this->path .'/'. $__template;
    }

    /**
     * partial loop
     *
     * <code>
     * <?php
     *  $data = array(2,4,6,8);
     *  $this->partialLoop('tr.phtml', $data, array('colspan'=>2));
     * ?>
     * <?php
     *  <tr>
     *    <th>
     *      <?=$key?>
     *    </th>
     *    <td colspan="<?=$colspan?>">
     *      <?=$value?>
     *    </td>
     *  </tr>
     * ?>
     * </code>
     *
     * @param       $template
     * @param array $data
     * @param array $params
     * @throws ViewException|\InvalidArgumentException
     * @return string
     */
    public function partialLoop($template, $data = array(), $params = array())
    {
        if (!file_exists($this->path .'/'. $template)) {
            throw new ViewException("Template '{$template}' not found");
        }

        if (!is_array($data)
            && (!$data instanceof \Traversable)
            && (is_object($data) && !method_exists($data, 'toArray'))
        ) {
            throw new \InvalidArgumentException('PartialLoop helper requires iterable data');
        }

        if (is_object($data)
            && (!$data instanceof \Traversable)
            && method_exists($data, 'toArray')
        ) {
            $data = $data->toArray();
        }

        foreach ($data as $key => $value) {
            $params['key'] = $key;
            $params['value'] = $value;
            $this->partial($template, $params);
        }
    }
}
