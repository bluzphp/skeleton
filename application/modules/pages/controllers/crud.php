<?php
/**
 * CRUD for pages
 *
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */
namespace Application;

return
/**
 * @privilege Management
 * @return \closure
 */
function() use ($view) {
    /**
     * @var \Bluz\Application $this
     */
    $crud = new Pages\Crud();
    return $crud->processController();
};
