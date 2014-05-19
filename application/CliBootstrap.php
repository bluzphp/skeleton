<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Application\Application;
use Bluz\Cli;

/**
 * Bootstrap for CLI
 *
 * @category Application
 * @package  Bootstrap
 *
 * @author   Anton Shevchuk
 * @created  17.12.12 15:24
 */
class CliBootstrap extends Application
{
    /**
     * Layout flag
     * @var boolean
     */
    protected $layoutFlag = false;

    /**
     * get CLI Request
     *
     * @return Cli\Request
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->request = new Cli\Request();
            if ($config = $this->getConfigData('request')) {
                $this->request->setOptions($config);
            }
        }
        return $this->request;
    }

    /**
     * get CLI Response
     *
     * @return Cli\Response
     */
    public function getResponse()
    {
        if (!$this->response) {
            $this->response = new Cli\Response();
            if ($config = $this->getConfigData('response')) {
                $this->response->setOptions($config);
            }
        }
        return $this->response;
    }

    /**
     * @return Application
     */
    public function finish()
    {
        if ($messages = $this->getLogger()->get('error')) {
            echo join("\n", $messages)."\n";
        }
        return $this;
    }
}
