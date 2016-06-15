<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests;

use Bluz\Application;
use Application\Bootstrap;
use Bluz\Application\Exception\ForbiddenException;
use Bluz\Application\Exception\RedirectException;
use Bluz\Application\Exception\ReloadException;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * Bootstrap
 *
 * @package  Application/Tests
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 17:38
 */
class BootstrapTest extends Bootstrap
{
    /**
     * Dispatched module name
     * @var string
     */
    protected $dispatchModule;

    /**
     * Dispatched controller name
     * @var string
     */
    protected $dispatchController;

    /**
     * @var \Exception
     */
    protected $exception;
    
    /**
     * resetRouter
     *
     * @return void
     */
    public function resetRouter()
    {
        $this->initRouter();
    }

    /**
     * Get dispatched module name
     *
     * @return string
     */
    public function getModule()
    {
        return $this->dispatchModule;
    }

    /**
     * Get dispatched controller name
     *
     * @return string
     */
    public function getController()
    {
        return $this->dispatchController;
    }

    /**
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return \Bluz\Controller\Controller
     * @throws \Exception
     */
    public function dispatch($module, $controller, $params = array())
    {
        $this->dispatchModule = $module;
        $this->dispatchController = $controller;

        try {
            return parent::dispatch($module, $controller, $params);
        } catch (\Exception $e) {
            $this->setException($e);
            throw $e;
        }
    }

    /**
     * setException
     *
     * @param \Exception $exception
     * @return void
     */
    public function setException($exception)
    {
        $this->exception = $exception;
    }

    /**
     * getException
     *
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}
