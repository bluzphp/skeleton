<?php
/**
 * Test AJAX
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 * @return closure
 */
namespace Application;

use Bluz\Controller\Data;

return
/**
 * @return \closure
 */
function () use ($data) {
    /**
     * @var Bootstrap $this
     * @var Data $data
     */
    $data->time = date('H:i:s');
};
