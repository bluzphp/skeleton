<?php
/**
 * Test of test of test
 *
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 */
namespace Application;

use Bluz\Proxy\Layout;

return
/**
 * @return \closure
 */
function () {
    /**
     * @var Bootstrap $this
     */
    Layout::title('Test Module');
    Layout::title('Append', Layout::POS_APPEND);
    Layout::title('Prepend', Layout::POS_PREPEND);
    Layout::breadCrumbs(['Test']);
};
