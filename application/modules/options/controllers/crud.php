<?php
/**
 * CRUD for options
 */
namespace Application;

return
/**
 * @privilege Management
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Bluz\Application $this
     */
    $crud = new Options\Crud();
    return $crud->processController();
};
