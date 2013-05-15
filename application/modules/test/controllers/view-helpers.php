<?php
/**
 * Demo of View helpers
 *
 * @category Application
 *
 * @author   dark
 * @created  14.05.13 16:12
 */
namespace Application;

return
/**
 * @return \closure
 */
function($sex = false, $car = 'none', $remember = false) use ($view) {
    /**
     * @var \Bluz\Application $this
     */
    $view->sex = $sex;
    $view->car = $car;
    $view->remember = $remember;
};