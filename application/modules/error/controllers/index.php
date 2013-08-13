<?php
/**
 * Error controller
 * Send error headers and show simple page
 *
 * @author   Anton Shevchuk
 * @created  11.07.11 15:32
 */
namespace Application;

use Bluz;
use Bluz\Request;

return
/**
 * @route  /error/{$code}
 * @param  int $code
 * @param  string $message
 *
 * @return \closure
 */
function ($code, $message = '') use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $this->getLogger()->error($message);

    switch ($code) {
        case 400:
            $header = "400 Bad Request";
            $description = "The request cannot be fulfilled due to bad syntax";
            break;
        case 401:
            $header = "401 Unauthorized";
            $description = "Access denied";
            break;
        case 403:
            $header = "403 Forbidden";
            $description = "You don't have permissions";
            break;
        case 404:
            $header = "404 Not Found";
            $description = "The page you requested was not found.";
            break;
        case 405:
            $header = "405 Method Not Allowed";
            $description = "The server is not support method";
            break;
        case 500:
            $header = "500 Internal Server Error";
            $description = "The server encountered an unexpected condition.";
            break;
        case 501:
            $header = "501 Not Implemented";
            $description = "The server does not understand or does not support the HTTP method.";
            break;
        case 503:
            $header = "503 Service Unavailable";
            $description = "The server is currently unable to handle the request due to a temporary overloading.";
            break;
        default:
            $code = 400;
            $header = "400 Bad Request";
            $description = "An unexpected error occurred with your request. Please try again later.";
            break;
    }

    // send headers, if possible
    if (!headers_sent()) {
        header("HTTP/1.1 {$header}");
        switch ($code) {
            case 405:
                header('Allow: '. $message);
                break;
            case 503:
                header('Retry-After: 600');
                break;
        }
    }

    // check CLI or HTTP request
    if ($this->getRequest()->getMethod() !== Request\AbstractRequest::METHOD_CLI) {
        $accept = $this->getRequest()->getHeader('accept');
        $accept = substr($accept, 0, strpos($accept, ','));

        // simple AJAX call
        if ($this->getRequest()->isXmlHttpRequest()
            && $accept == "application/json") {
            $this->getMessages()->addError($message);
            return $view;
        }

        // dialog AJAX call
        if (!$this->getRequest()->isXmlHttpRequest()) {
            $this->useLayout('small.phtml');
        }
    }

    $view->title = $header;
    $view->description = $description;
    $view->message = $message;
    $this->getLayout()->title($header);
};
