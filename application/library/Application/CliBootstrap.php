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

use Application\Users\Table;
use Bluz\Application\Application;
use Bluz\Application\Exception\ApplicationException;
use Bluz\Cli;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;
use Bluz\Request\RequestFactory;

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
     * @return void
     * @throws ApplicationException
     */
    public function initRequest()
    {
        $arguments = getopt("u:", ["uri:"]);

        if (!array_key_exists('u', $arguments) && !array_key_exists('uri', $arguments)) {
            throw new ApplicationException('Attribute `--uri` is required');
        }

        $uri = isset($arguments['u']) ? $arguments['u'] : $arguments['uri'];

        $request = RequestFactory::fromGlobals(['REQUEST_URI' => $uri, 'REQUEST_METHOD' => 'CLI']);

        Request::setInstance($request);
    }

    /**
     * Pre process
     * @return void
     */
    protected function preProcess()
    {
        Logger::info("app:process:pre");
        Router::process();
        Response::switchType('CLI');
    }

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
        // auth as CLI user
        $cliUser = Table::findRowWhere(['login' => 'system']);
        Auth::setIdentity($cliUser);

        parent::preDispatch($module, $controller, $params);
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
                errorLog(E_USER_ERROR, $message);
            }
        }

        // return code 1 for invalid behaviour of application
//        if ($exception = $this->getException()) {
//            echo $exception->getMessage();
//            exit(1);
//        }
        exit;
    }
}
