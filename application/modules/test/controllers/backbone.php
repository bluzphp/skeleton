<?php
/**
 * Example of backbone usage
 *
 * @category Application
 *
 * @author   dark
 * @created  13.08.13 17:16
 */
namespace Application;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $this->getLayout()->ahref('Test', ['test', 'index']),
            'Backbone',
        ]
    );
};
