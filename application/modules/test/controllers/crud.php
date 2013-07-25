<?php
/**
 * Example of Crud
 *
 * @author   Anton Shevchuk
 * @created  04.09.12 11:21
 */
namespace Application;

use Application\Test;
use Bluz;
use Bluz\Request\AbstractRequest;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $crud = new Test\Crud();
    return $crud->processController();
};
