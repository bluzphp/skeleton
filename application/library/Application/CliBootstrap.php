<?php
/**
 * Bluz Framework Component
 *
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Application\Application;
use Bluz\Cli;
use Bluz\Proxy\Config;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;

/**
 * Bootstrap for CLI
 *
 * @category Application
 * @package  Bootstrap
 *
 * @author   Anton Shevchuk
 * @created  17.12.12 15:24
 */
class CliBootstrap extends Application
{
    /**
     * Layout flag
     * @var boolean
     */
    protected $layoutFlag = false;

    /**
     * get CLI Request
     *
     * @return Cli\Request
     */
    public function initRequest()
    {
        $request = new Cli\Request();
        if ($config = Config::getData('request')) {
            $request->setOptions($config);
        }
        Request::setInstance($request);
    }

    /**
     * get CLI Response
     *
     * @return Cli\Response
     */
    public function initResponse()
    {
        $response = new Cli\Response();
        if ($config = Config::getData('response')) {
            $response->setOptions($config);
        }
        Response::setInstance($response);
    }

    /**
     * Render, is send Response
     *
     * @return void
     */
    public function render()
    {
        Logger::info('app:render');
        Response::send();
    }

    /**
     * @return void
     */
    public function finish()
    {
        if ($messages = Logger::get('error')) {
            foreach ($messages as $message) {
                errorLog($message);
            }
        }

        // return code 1 for invalid behaviour of application
        if ($exception = Response::getException()) {
            echo $exception->getMessage();
            exit(1);
        }
        exit;
    }
}
