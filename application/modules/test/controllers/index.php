<?php
/**
 * Test of test of test
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Layout;

/**
 * @return \closure
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::title('Test Module');
    Layout::title('Append', Layout::POS_APPEND);
    Layout::title('Prepend', Layout::POS_PREPEND);
    Layout::breadCrumbs(['Test']);
};
