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
namespace Application\Auth;

use Bluz\Application;
use Bluz\Auth\AuthException;

/**
 * @category Application
 * @package  Auth
 *
 * @author   Anton Shevchuk
 * @created  12.07.11 15:28
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'auth';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('provider', 'foreignKey');


    /**
     * getAuthRow
     *
     * @todo foreign key for equals provider is equal to user logn -_- ?
     * @param $provider
     * @param $foreignKey
     * @return Row
     */
    public function getAuthRow($provider, $foreignKey)
    {
        return $this->findRow([
            'provider' => $provider,
            'foreignKey' => $foreignKey
        ]);
    }

    /**
     * authenticate user by login/pass
     *
     * @param string $username
     * @param string $password
     * @throws \Application\Exception
     * @throws \Bluz\Auth\AuthException
     * @return boolean
     */
    public function authenticateEquals($username, $password)
    {
        /** @var $user \Application\Users\Row */
        $authRow = $this->getAuthRow(Row::PROVIDER_EQUALS, $username);

        if (!$authRow) {
            throw new AuthException("User not found");
        }

        $auth = Application::getInstance()->getAuth();
        $options = $auth->getOption(Row::PROVIDER_EQUALS);

        if (!isset($options['encryptFunction']) or
            !is_callable($options['encryptFunction'])
        ) {
            throw new \Application\Exception("Encryption function for 'equals' adapter is not callable");
        }

        // encrypt password
        $password = call_user_func($options['encryptFunction'],  $password, $authRow->tokenSecret);

        if ($password != $authRow->token) {
            throw new AuthException("Wrong password");
        }

        // get user profile
        $user = \Application\Users\Table::getInstance()->findRow($authRow->userId);
        // try to login
        $user->login();

        return true;
    }

    /**
     * authenticate user by login/pass
     *
     * @param \Application\Users\Row $user
     * @param string $password
     * @throws \Application\Exception
     * @throws \Bluz\Auth\AuthException
     * @return boolean
     */
    public function generateEquals($user, $password)
    {
        $auth = Application::getInstance()->getAuth();
        $options = $auth->getOption(Row::PROVIDER_EQUALS);

        if (!isset($options['encryptFunction']) or
            !is_callable($options['encryptFunction'])
        ) {
            throw new \Application\Exception("Encryption function for 'equals' adapter is not callable");
        }

        // new auth row
        $row = new Row();
        $row->userId = $user->id;
        $row->foreignKey = $user->login;
        $row->provider = Row::PROVIDER_EQUALS;
        $row->tokenType = Row::TYPE_ACCESS;

        // generate secret
        $secret = array_rand(range('a', 'z', rand(1,5)));
        $secret = md5($user->id . join('', $secret));
        $row->tokenSecret = $secret;

        // encrypt password and save as token
        $row->token = call_user_func($options['encryptFunction'],  $password, $secret);

        $row->save();

        return $row;
    }
}
