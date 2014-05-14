<?php
/**
 * Mailer for Users
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  06.12.12 12:43
 */
namespace Application;

return
/**
 * @param string $template
 * @param array $vars
 * @return \closure
 */
function ($template, $vars = []) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $view->setTemplate('mail/' . $template . '.phtml');
    return $vars;
};
