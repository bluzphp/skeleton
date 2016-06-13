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
 * @return void
 */
return function () {
    /**
     * @var Controller $this
     */
    $this->disableView();
    $total = Db::fetchOne('SELECT COUNT(*) FROM users');
    $active = Db::fetchOne('SELECT COUNT(*) FROM users WHERE status = ?', [Table::STATUS_ACTIVE]);
    $last = Db::fetchRow('SELECT id, login FROM users ORDER BY id DESC LIMIT 1');
    ?>
    <script>
        require(['bluz.widget']);
    </script>
    <div class="widget col-4" data-widget-key="users-stats">
        <div class="widget-title">
            <span class="iconholder-left"><i class="fa fa-signal"></i></span>
            <h4>Users</h4>
            <span class="iconholder-right widget-control" data-widget-control="collapse">
                <i class="fa fa-chevron-up"></i>
            </span>
        </div>
        <div class="widget-content">
            <ul class="widget-stats">
                <li>
                    <a href="<?=Router::getUrl('users', 'grid')?>">
                    <i class="fa fa-user fa-fw"></i>
                    <strong><?=$total?></strong>
                    <small><?=__('Total Users')?></small>
                    </a>
                </li>
                <li>
                    <a href="
                    <?=Router::getUrl('users', 'grid', ['users-filter-status' => Table::STATUS_ACTIVE])?>">
                    <i class="fa fa-eye fa-fw"></i>
                    <strong><?=$active?></strong>
                    <small><?=__('Active Users')?></small>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?=Router::getUrl('users', 'profile', ['id' => $last['id']])?>">
                    <i class="fa fa-user fa-fw"></i>
                    <strong><?=$last['login']?></strong>
                    <small><?=__('Last Registers')?></small>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php
};
