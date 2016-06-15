<?php
/**
 * Example of privilege usage - denied access
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  21.05.13 16:55
 */
namespace Application;

use Bluz\Controller\Controller;

/**
 * @accept HTML
 * @accept JSON
 * @privilege Denied
 *
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
};
