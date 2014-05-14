<?php
/**
 * 
 *
 * @category Application
 *
 * @author   dark
 * @created  18.12.13 18:39
 */
namespace Application;

return
/**
 * @route /test/route-with-other-params/{$alias}(.*)
 */
function ($alias) use ($module, $controller, $view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    var_dump($alias);
    var_dump($this->getRequest()->getParams());
    var_dump($this->getRequest()->getAllParams());
    var_dump($this->getRequest()->getRawParams());
    return false;
};
