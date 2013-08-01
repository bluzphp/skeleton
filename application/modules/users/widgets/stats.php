<?php
/**
 * @author   Anton Shevchuk
 * @created  22.10.12 18:40
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
    $total = $this->getDb()->fetchOne('SELECT COUNT(*) FROM users');
    $active = $this->getDb()->fetchOne('SELECT COUNT(*) FROM users WHERE status = ?', [Users\Row::STATUS_ACTIVE]);
    $last = $this->getDb()->fetchRow('SELECT id, login FROM users ORDER BY id DESC LIMIT 1');
    ?>
    <script>
        require(['bluz.widget']);
    </script>
    <div class="widget col-4" data-widget-key="users-stats">
        <div class="widget-title">
            <span class="iconholder-left"><i class="glyphicon glyphicon-signal"></i></span>
            <h4>Users</h4>
            <span class="iconholder-right widget-control" data-widget-control="collapse">
                <i class="glyphicon glyphicon-chevron-up"></i>
            </span>
        </div>
        <div class="widget-content">
            <ul class="widget-stats">
                <li>
                    <i class="glyphicon glyphicon-user"></i>
                    <strong><?=$total?></strong>
                    <small><?=__('Total Users')?></small>
                </li>
                <li>
                    <i class="glyphicon glyphicon-eye-open"></i>
                    <strong><?=$active?></strong>
                    <small><?=__('Active Users')?></small>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="/users/profile/id/<?=$last['id']?>">
                    <i class="glyphicon glyphicon-user"></i>
                    <strong><?=$last['login']?></strong>
                    <small><?=__('Last Registers')?></small>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php
};
