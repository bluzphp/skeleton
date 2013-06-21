<?php
/**
 * Bootstrap
 *
 * @category Application
 * @package  Bootstrap
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 17:38
 */
namespace Application\Tests;

use Bluz\Application;
use Bluz\Exception;
use Bluz\Request;

class BootstrapTest extends Application
{
    /**
     * getRequest
     *
     * @return \Bluz\Request\HttpRequest
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->request = new Request\HttpRequest($this->getConfigData('request'));
        }
        return $this->request;
    }
}
