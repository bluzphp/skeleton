<?php

/**
 * Copyright (c) 2012 by Bluz PHP Team
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
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Bluz\View;

use Bluz\Package;
use Bluz\Options;

/**
 * View
 *
 * @category Bluz
 * @package  View
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 11:49
 *
 * @property mixed _messages
 * @property mixed content
 */
class Layout extends View
{
    /**
     * @var mixed
     */
    protected $_content;

    /**
     * @param      $name
     * @param null $target
     * @return \Bluz\EventManager\EventManager
     */
    public function trigger($name, $target = null)
    {
        return $this->getApplication()->getEventManager()->trigger('layout:'.$name, $target);
    }

    /**
     * Set content
     *
     * @param mixed $content
     * @return View
     */
    public function setContent($content)
    {
        try {
            switch (true) {
                case ($content instanceof \Closure):
                    $content = $content();
                    break;
                case ($content instanceof View):
                    $content = $content->render();
                    break;
            }
            $this->_content = $content;
        } catch (\Exception $e) {
            $this->_content = $e->getMessage();
        }
        return $this;
    }

    /**
     * Set content
     *
     * @return View
     */
    public function getContent()
    {
        $this->_content = $this->trigger('content', $this->_content);


        return $this->_content;
    }
}
