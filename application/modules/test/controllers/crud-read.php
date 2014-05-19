<?php
/**
 * Read of CRUD
 *
 * @category Application
 *
 * @author   dark
 * @created  14.05.13 10:50
 */
namespace Application;

return
/**
 * @method GET
 * @param int $id
 * @return \closure
 */
function ($id = null) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     * @var Test\Row $row
     */
    if ($row = Test\Table::findRow($id)) {
        $view->row = $row;
    } else {
        throw new Exception('Record not found');
    }
};
