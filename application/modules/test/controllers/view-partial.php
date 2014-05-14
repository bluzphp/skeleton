<?php
/**
 * Test of partial methods of View
 * 
 * @author   Anton Shevchuk
 * @created  13.10.11 12:39
 * @return closure
 */
namespace Application;

use Bluz;

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
            $view->ahref('Test', ['test', 'index']),
            'View Partial Helpers',
        ]
    );
    $view->data = array(
        'first'=> array(2,3,4,5),
        'second'=> array(9,8,7,6),
        'third'=> array(1,3,5,7),
    );
};
