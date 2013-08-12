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
use Bluz\Request;
use Application\Bootstrap;

class BootstrapTest extends Bootstrap
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
