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
return
/**
 * @route  /error/{$code}
 * @param  int $code
 * @param  string $message
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
            $description = "Access denied";
            break;
        case 404:
            $header = "404 Not Found";
            $description = "The page you requested was not found.";
            break;
        case 500:
            $header = "500 Internal Server Error";
            $description = "The server encountered an unexpected condition.";
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
    if (!headers_sent()) {
        header("HTTP/1.1 {$header}");
        if ($code == 503) {
            header('Retry-After: 600');
        }
    }


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

    $view->title = $header;
    $view->description = $description;
    $view->message = $message;
    $this->getLayout()->title($header);
};
