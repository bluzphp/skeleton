<?php
/**
 * Error controller
 * Send error headers and show simple page
 *
 * @author   Anton Shevchuk
 * @created  11.07.11 15:32
 */
namespace Bluz;
return
/**
 * @param  integer $code
 * @param  string $message
 * @return \closure
 */
function ($code, $message = '') use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    switch ($code) {
        case 403:
            $header = "403 Forbidden";
            $description = "Access denied";
            break;
        case 404:
            $header = "404 Not Found";
            $description = "The page you requested was not found.";
            break;
        default:
            $header = "400 Bad Request";
            $description = "An unexpected error occurred with your request. Please try again later.";
            break;
    }
    if (!headers_sent()) header("HTTP/1.1 {$header}");

    $this->getLayout()->title($header);

    if ($this->getRequest()->isXmlHttpRequest()) {
        $this->getMessages()->addError($message);
    } else {
        $view->title = $this->getLayout()->title();
        $view->description = $description;
        $view->message = $message;
        $this->useLayout('small.phtml');
    }
};
