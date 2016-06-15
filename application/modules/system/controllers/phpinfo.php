<?php
/**
 * PHP Info Wrapper
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

/**
 * @privilege Info
 *
 * @return void
 */
return function () {
    phpinfo();
    die;
};
