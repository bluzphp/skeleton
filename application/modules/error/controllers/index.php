<?php
/**
 * Error controller
 * Send error headers and show simple page
 *
 * @author   Anton Shevchuk
 * @created  11.07.11 15:32
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Proxy\Layout;
use Bluz\Proxy\Logger;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Response;
use Bluz\Proxy\Request;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * @param $code
 * @param string $message
 * @return \Bluz\View\View
 */
return
/**
 * @route  /error/{$code}
 * @param  int $code
 * @param  string $message
 */
function ($code, $message = '') use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Logger::error($message);

    switch ($code) {
        case 400:
            $title = __("Bad Request");
            $description = __("The server didn't understand the syntax of the request");
            break;
        case 401:
            $title = __("Unauthorized");
            $description = __("You are not authorized to view this page, please sign in");
            break;
        case 403:
            $title = __("Forbidden");
            $description = __("You don't have permissions to access this page");
            break;
        case 404:
            $title = __("Not Found");
            $description = $message ?: __("The page you requested was not found");
            break;
        case 405:
            $title = __("Method Not Allowed");
            $description = __("The server is not support method");
            Response::setHeader('Allow', $message);
            break;
        case 406:
            $title = __("Not Acceptable");
            $description = __("The server is not acceptable generating content type described at `Accept` header");
            break;
        case 500:
            $title = __("Internal Server Error");
            $description = __("The server encountered an unexpected condition");
            break;
        case 501:
            $title = __("Not Implemented");
            $description = __("The server does not understand or does not support the HTTP method");
            break;
        case 503:
            $title = __("Service Unavailable");
            $description = __("The server is currently unable to handle the request due to a temporary overloading");
            Response::setHeader('Retry-After', '600');
            break;
        default:
            $code = 500;
            $title = __("Internal Server Error");
            $description = __("An unexpected error occurred with your request. Please try again later");
            break;
    }

    // check CLI or HTTP request
    if (Request::isHttp()) {
        // simple AJAX call, accept JSON
        if (Request::getAccept() == Request::ACCEPT_JSON) {
            $this->useJson();
            Messages::addError($description);
            return $view;
        }
        // dialog AJAX call, accept HTML
        if (!Request::isXmlHttpRequest()) {
            $this->useLayout('small.phtml');
        }
    }

    Layout::title($title);
    $view->error = $title;
    $view->description = $description;

    if ($this->isDebug() && ($e = Response::getException()) && $code >= 500) {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->handleException($e);
        return false;
    }
    return $view;
};
