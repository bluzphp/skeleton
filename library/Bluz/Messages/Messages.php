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
namespace Bluz\Messages;

use Bluz\Package;

/**
 * Realization of Flash Messages
 *
 * @category Bluz
 * @package  Messages
 *
 * @author   Anton Shevchuk
 */
class Messages extends Package
{
    /**
     * Messages
     * @var array
     */
    protected $_messages = array(
        'info' => array(),
        'success' => array(),
        'error' => array(),
    );

    /**
     * Save messages to session store
     *
     * @return Messages
     */
    public function save()
    {
        if ($this->total()) {
            $this->getApplication()->getSession()->MessagesStore = $this->getStore();
        }

        return $this;
    }

    /**
     * Restore messages from session store
     *
     * @return Messages
     */
    public function restore()
    {
        if ($this->getApplication()->getSession()->MessagesStore) {
            $this->setStore($this->getApplication()->getSession()->MessagesStore);
        }

        return $this;
    }

    /**
     * get size of messages container
     *
     * @return integer
     */
    public function total()
    {
        $size = 0;
        foreach ($this->_messages as $messages) {
            $size += sizeof($messages);
        }
        return $size;
    }

    /**
     * getMessages
     *
     * @return array
     */
    public function getStore()
    {
        return $this->_messages;
    }

    /**
     * setMessages
     *
     * @param $messagesStore
     * @return array
     */
    public function setStore($messagesStore)
    {
        return $this->_messages = $messagesStore;
    }


    /**
     * add notice
     *
     * @param string $text
     * @return Messages
     */
    public function addNotice($text)
    {
        $this->_messages['info'][] = $text;
        return $this;
    }

    /**
     * add success
     *
     * @param string $text
     * @return Messages
     */
    public function addSuccess($text)
    {
        $this->_messages['success'][] = $text;
        return $this;
    }

    /**
     * add error
     *
     * @param string $text
     * @return Messages
     */
    public function addError($text)
    {
        $this->_messages['error'][] = $text;
        return $this;
    }
}