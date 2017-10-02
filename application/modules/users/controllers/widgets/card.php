<?php
/**
 * @author   Anton Shevchuk
 * @created  22.10.12 18:40
 */

namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Db;

/**
 * @privilege Management
 *
 * @return string
 */
return function ($id = null) {
    /**
     * @var Controller $this
     */
    if (!$id) {
        $id = Db::fetchOne('SELECT id FROM users ORDER BY id DESC LIMIT 1');
    }

    $this->assign('user', Users\Table::findRow($id));
    return 'widgets/card.phtml';
};
