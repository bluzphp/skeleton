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
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Application\UsersActions;

/**
 * Table
 *
 * @category Application
 * @package  Users
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'users_actions';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('userId', 'code');

    /**
     * generate action with token
     *
     * @param int $userId
     * @param string $action
     * @param int $expired in days
     * @return Row
     */
    public function generate($userId, $action, $expired = 5)
    {
        // remove previously generated tokens
        $this->delete(['userId' => $userId, 'action' => $action]);

        // create new row
        $actionRow = new Row();
        $actionRow->userId = $userId;
        $actionRow->action = $action;
        $random = range('a', 'z', rand(1,5));
        shuffle($random);
        $actionRow->code = md5($userId . $action . join('', $random) . time());
        $actionRow->expired = date('Y-m-d H:i:s', strtotime("+$expired day"));
        $actionRow->save();

        return $actionRow;
    }
}