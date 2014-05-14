<?php
/**
 * Delete of CRUD
 *
 * @category Application
 *
 * @author   dark
 * @created  14.05.13 10:51
 */
namespace Application;

return
/**
 * @method DELETE
 * @param int $id
 * @return \closure
 */
function ($id) use ($view) {
    /**
     * @var Bootstrap $this
     */
    if ($row = Test\Table::findRow($id)) {
        $row->delete();
        $this->redirectTo('test', 'index');
        $this->getMessages()->addSuccess("Row was removed");
    } else {
        throw new Exception('Record not found');
    }
};
