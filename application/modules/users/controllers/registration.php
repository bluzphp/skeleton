<?php
/**
 * User registration
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  09.11.12 13:19
 */
namespace Application;

return
/**
 * @return \closure
 */
function () {
    /**
     * @var \Bluz\Application $this
     */
    // change layout
    $this->useLayout('small.phtml');

    $crud = new Users\Crud();
    return $crud->processController();
};