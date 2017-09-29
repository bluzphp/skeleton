<?php
/**
 * @author   Anton Shevchuk
 * @created  22.10.12 18:40
 */

namespace Application;

use Application\Users\Table;
use Bluz\Controller\Controller;
use Bluz\Proxy\Db;
use Bluz\Proxy\Router;

/**
 * @privilege Management
 *
 * @return string
 */
return function () {
    /**
     * @var Controller $this
     */
    $this->assign('total', Db::fetchOne('SELECT COUNT(*) FROM users'));
    $this->assign('active', Db::fetchOne('SELECT COUNT(*) FROM users WHERE status = ?', [Table::STATUS_ACTIVE]));
    $this->assign('last', Db::fetchRow('SELECT id, login FROM users ORDER BY id DESC LIMIT 1'));
    return 'widgets/stats.phtml';
};
