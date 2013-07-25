<?php
/**
 * Get custom reflection data
 *
 * @category Application
 *
 * @author   dark
 * @created  17.05.13 17:05
 */
namespace Application;

return
/**
 * @resource Example
 * @custom First
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $view->reflectionData = $this->reflection(__FILE__);
};
