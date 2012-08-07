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
            $description = "Access denied";
            break;
        case 404:
            $description = "The page you requested was not found.";
            break;
        default:
            $code = 400;
            $description = "An unexpected error occurred with your request. Please try again later.";
            break;
    }
    if (!headers_sent()) header("HTTP/1.0 {$code} {$message}");
    $this->getLayout()->title("{$message} {$code}");

    if ($this->getRequest()->isXmlHttpRequest()) {
        $this->getMessages()->addError($message);
    } else {
        $view->title = $this->getLayout()->title();
        $view->description = $description;
        $view->message = $message;
        $this->useLayout('small.phtml');
    }
};
