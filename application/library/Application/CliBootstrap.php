<?php
/**
 * Bluz Framework Component
 *
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/framework
 */

declare(strict_types=1);

namespace Application;

use Application\Users\Table;
use Bluz\Application\Application;
use Bluz\Controller\Controller;
use Bluz\Proxy\Auth;
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
    public function setInput(InputInterface $input): void
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
    public function setOutput(OutputInterface $output): void
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
     */
    public function initRequest(): void
    {
        $uri = $this->getInput()->getArgument('uri');

        $parsedQuery = parse_url($uri, PHP_URL_QUERY);
        if (false !== $parsedQuery && null !== $parsedQuery) {
            parse_str($parsedQuery, $query);
        } else {
            $query = [];
        }

        $request = RequestFactory::fromGlobals(['REQUEST_URI' => $uri, 'REQUEST_METHOD' => 'CLI'], $query);

        Request::setInstance($request);
    }

    /**
     * Pre process
     *
     * @return void
     */
    protected function preProcess(): void
    {
        Router::process();
        Response::setType('CLI');
    }

    /**
     * {@inheritdoc}
     *
     * @param Controller $controller
     *
     * @return void
     * @throws \Bluz\Db\Exception\DbException
     */
    protected function preDispatch($controller): void
    {
        // auth as CLI user
        if ($cliUser = Table::findRowWhere(['login' => 'system'])) {
            Auth::setIdentity($cliUser);
        }

        parent::preDispatch($controller);
    }

    /**
     * Render, is send Response
     *
     * @return void
     */
    public function render(): void
    {
        $io = new SymfonyStyle($this->getInput(), $this->getOutput());
        $io->title('Bluz CLI');

        if ($params = Request::getParams()) {
            foreach ($params as $key => $value) {
                $key = \is_int($key) ? "<comment>$key</comment>" : "<info>$key</info>";
                $io->writeln("$key: $value");
            }
        }

        $io->writeln('');
        $io->writeln('========');
        $io->writeln('');
        $io->writeln(json_encode(Response::getBody()));
        $io->writeln('');
    }

    /**
     * Finish it
     *
     * @return void
     */
    public function end(): void
    {
        if ($errors = Logger::get('error')) {
            $this->sendErrors($errors);
        }
        // return code 1 for invalid behaviour of application
//        if ($exception = $this->getException()) {
//            echo $exception->getMessage();
//            exit(1);
//        }
        exit;
    }

    /**
     * Send errors to logger
     *
     * @param  array $errors
     *
     * @return void
     */
    protected function sendErrors($errors): void
    {
        foreach ($errors as $message) {
            errorLog(new \ErrorException($message, 0, E_USER_ERROR));
        }
    }
}
