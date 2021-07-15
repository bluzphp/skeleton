<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application;

use Application\Auth;
use Bluz\Application\Application;
use Bluz\Auth\AuthException;
use Bluz\Controller\Controller;
use Bluz\Http\Exception\ForbiddenException;
use Bluz\Http\Exception\RedirectException;
use Bluz\Proxy\Auth as AuthProxy;
use Bluz\Proxy\Config;
use Bluz\Proxy\Layout;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;
use Bluz\Proxy\Session;
use Bluz\Proxy\Translator;

/**
 * Bootstrap
 *
 * @category Application
 * @package  Bootstrap
 */
class Bootstrap extends Application
{
    /**
     * {@inheritdoc}
     */
    protected function initTranslator(): void
    {
        $translator = new \Bluz\Translator\Translator();
        $translator->setOptions(Config::get('translator'));

        // supported locales
        // en_US, ru_RU, uk_UA
        $locales = [
            'en' => 'en_US',
            'ru' => 'ru_RU',
            'uk' => 'uk_UA',
        ];

        // try to check locale from cookies
        $locale = Request::getCookie('locale');

        // try to get locale from browser
        if (!$locale) {
            $languages = Request::getAcceptLanguage();
            foreach ($languages as $language => $priority) {
                if (array_key_exists($language, $locales)) {
                    $locale = $language;
                    break;
                }
            }
        }

        // normalize locale
        if (array_key_exists($locale, $locales)) {
            $translator->setLocale($locales[$locale]);
        }

        $translator->init();

        Translator::setInstance($translator);
    }

    /**
     * {@inheritdoc}
     *
     * @param Controller $controller
     *
     * @return void
     * @throws AuthException
     * @throws Exception
     * @throws \Bluz\Db\Exception\DbException
     * @throws \Bluz\Db\Exception\InvalidPrimaryKeyException
     */
    protected function preDispatch($controller): void
    {
        // example of setup default title
        Layout::title('Bluz Skeleton');

        if (!AuthProxy::getIdentity() && $controller->getModule() !== Router::getErrorModule()) {
            if ($token = Request::getCookie('Auth-Token')) {
                // try to login by token from cookies
                try {
                    Auth\Provider\Cookie::authenticate($token);
                } catch (AuthException $e) {
                    $this->getResponse()->setCookie('Auth-Token', '', 1, '/');
                }
            } elseif ($token = Request::getHeader('Auth-Token')) {
                // try to login by token from headers
                Auth\Provider\Token::authenticate($token);
            }
        }
        parent::preDispatch($controller);
    }

    /**
     * Denied access
     *
     * @param ForbiddenException $exception
     *
     * @return \Bluz\Controller\Controller|null
     */
    public function forbidden(ForbiddenException $exception): ?Controller
    {
        // for AJAX and API calls (over JSON)
        $jsonOrApi = Request::isXmlHttpRequest()
            || (Request::checkAccept([Request::TYPE_HTML, Request::TYPE_JSON]) === Request::TYPE_JSON);

        // for guest, for requests
        if (!$jsonOrApi && !AuthProxy::getIdentity()) {
            // save URL to session and redirect make sense if presentation is null
            Session::set('rollback', Request::getUri()->__toString());
            // add error notice
            Messages::addError('You don\'t have permissions, please sign in');
            // redirect to Sign In page
            $redirect = new RedirectException();
            $redirect->setUrl(Router::getUrl('users', 'signin'));
            return $this->redirect($redirect);
        }
        return $this->error($exception);
    }

    /**
     * Render with debug headers
     *
     * @return void
     */
    public function render(): void
    {
        Logger::info('app:render');
        Logger::info('app:files:' . count(get_included_files()));

        if ($this->isDebug()) {
            if (!headers_sent()) {
                $this->sendInfoHeaders();
            }
            if (ob_get_level() > 0 && ob_get_length() > 0) {
                Logger::error('Output has been sent previously');
                return;
            }
        }
        parent::render();
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
    }

    /**
     * Send information headers
     *
     * @return void
     */
    protected function sendInfoHeaders(): void
    {
        $debugString = sprintf(
            '%fsec; %skb',
            microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
            ceil(memory_get_usage() / 1024)
        );
        $debugString .= '; ' . Request::getModule() . '/' . Request::getController();

        Response::setHeader('Bluz-Debug', $debugString);

        if ($info = Logger::get('info')) {
            Response::setHeader('Bluz-Bar', json_encode($info));
        } else {
            Response::setHeader('Bluz-Bar', '{"!":"Logger is disabled"}');
        }
    }

    /**
     * sendErrorBody
     *
     * @param array $errors
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
