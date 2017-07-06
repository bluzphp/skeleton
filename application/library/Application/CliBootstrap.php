<?php
/**
 * Bluz Framework Component
 *
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */

namespace Application;

use Application\Users\Table;
use Bluz\Application\Application;
use Bluz\Application\Exception\ApplicationException;
use Bluz\Controller\Controller;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Config;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;
use Bluz\Request\RequestFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
     *
     * @var boolean
     */
    protected $layoutFlag = false;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param InputInterface $input
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @return InputInterface
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * get CLI Request
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws ApplicationException
     */
    public function initRequest()
    {
        $uri = $this->getInput()->getArgument('uri');

        $parsedQuery = parse_url($uri, PHP_URL_QUERY);

        parse_str($parsedQuery, $query);

        $request = RequestFactory::fromGlobals(['REQUEST_URI' => $uri, 'REQUEST_METHOD' => 'CLI'], $query);

        Request::setInstance($request);
    }

    /**
     * initConfig
     *
     * @return void
     * @throws \Bluz\Config\ConfigException
     */
    public function initConfig()
    {
        $config = new \Bluz\Config\Config();
        $config->setPath(self::getInstance()->getPath());
        $config->setEnvironment(self::getInstance()->getEnvironment());
        $config->init();

        Config::setInstance($config);

        parent::initConfig();
    }

    /**
     * Pre process
     *
     * @return void
     */
    protected function preProcess()
    {
        Router::process();
        Response::switchType('CLI');
    }

    /**
     * {@inheritdoc}
     *
     * @param Controller $controller
     *
     * @return void
     */
    protected function preDispatch($controller)
    {
        // auth as CLI user
        $cliUser = Table::findRowWhere(['login' => 'system']);
        Auth::setIdentity($cliUser);

        parent::preDispatch($controller);
    }

    /**
     * Render, is send Response
     *
     * @return void
     */
    public function render()
    {
        $io = new SymfonyStyle($this->getInput(), $this->getOutput());
        $io->title('Bluz CLI');

        if ($params = Request::getParams()) {
            foreach ($params as $key => $value) {
                $key = is_int($key) ? "<comment>$key</comment>" : "<info>$key</info>";
                $io->writeln("$key: $value");
            }
        }

        $io->writeln('');
        $io->writeln('========');
        $io->writeln('');

        $data = Response::getBody()->getData()->toArray();

        foreach ($data as $key => $value) {
            $io->writeln("<info>$key</info>: $value");
        }

        $io->writeln('');
    }

    /**
     * @return void
     */
    public function end()
    {
        if ($messages = Logger::get('error')) {
            foreach ($messages as $message) {
                errorLog(new \ErrorException($message, 0, E_USER_ERROR));
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
