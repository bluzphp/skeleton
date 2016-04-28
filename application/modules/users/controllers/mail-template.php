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

use Bluz\Controller\Controller;

return
/**
 * @param string $template
 * @param array $vars
 * @return \closure
 */
function ($template, $vars = []) {
    /**
     * @var Controller $this
     */
    $this->template = 'mail/' . $template . '.phtml';
    return $vars;
};
