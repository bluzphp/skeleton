<?php
/**
 * Test CLI - denied access
 *
 * @category Example
 *
 * @author   Anton Shevchuk
 * @created  18.11.12 19:41
 */
namespace Application;

use Bluz\Controller\Controller;

/**
 * @method GET
 *
 * @param  bool $flag
 * @return array
 */
return function ($flag = false) {
    /**
     * @var Controller $this
     */
    return ['string' => 'bar', 'array' => ['some', 'array'], 'flag' => $flag];
};
