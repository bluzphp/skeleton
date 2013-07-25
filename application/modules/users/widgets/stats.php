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
    ?>
    <script>
        require(['bluz.widget']);
    </script>
    <div class="widget span3" data-widget-key="users-stats">
        <div class="widget-title">
            <span class="iconholder-left"><i class="icon-signal"></i></span>
            <h4>Users</h4>
            <span class="iconholder-right widget-control" data-widget-control="collapse">
                <i class="icon-chevron-up"></i>
            </span>
        </div>
        <div class="widget-content">
            <ul class="widget-stats">
                <li>
                    <i class="icon-user"></i>
                    <strong>123</strong>
                    <small>Total Users</small>
                </li>
                <li>
                    <i class="icon-eye-open"></i>
                    <strong>23</strong>
                    <small>Active Users</small>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="/users/profile/id/1">
                    <i class="icon-user"></i>
                    <strong>admin</strong>
                    <small>Last Registers</small>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php
};
