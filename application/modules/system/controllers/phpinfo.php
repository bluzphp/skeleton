<?php
/**
 * PHP Info Wrapper
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

return
/**
 * @privilege Info
 *
 * @return \closure
 */
function () {
    phpinfo();
    return false;
};
