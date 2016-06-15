<?php
/**
 * Exception inside controller
 *
 * @category Example
 *
 * @author   Anton
 * @created  12/28/13 8:32 AM
 */
namespace Application;

use Bluz\Controller\Controller;

/**
 * @return void
 * @throws \Exception
 */
return function () {
    /**
     * @var Controller $this
     */
    throw new \Exception("Example of exception");
};
