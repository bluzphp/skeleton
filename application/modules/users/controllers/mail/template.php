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

/**
 * @param  string $template
 * @param  array $vars
 * @return array
 */
return function ($template, $vars = []) {
    /**
     * @var Controller $this
     */
    $this->template = 'mail/' . $template . '.phtml';
    return $vars;
};
