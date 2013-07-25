<?php
/**
 * Mailer for Users
 *
 * @category Application
 *
 * @author   dark
 * @created  06.12.12 12:43
 */
namespace Application;

return
/**
 * @return \closure
 */
function ($template, $vars = []) use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $view->setTemplate('mail/' . $template . '.phtml');
    return $vars;
};
