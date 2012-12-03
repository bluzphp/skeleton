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
namespace Application;

use Bluz\Application;
use Bluz\Exception;

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
            $this->request = new \Bluz\Request\HttpRequest($this->getConfigData('request'));
        }
        return $this->request;
    }
}
