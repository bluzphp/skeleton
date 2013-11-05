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
namespace Application\Auth;

use Application\Exception;
use Application\Users;
use Bluz\Application;

use Bluz\Auth\AuthException;

/**
 * Auth Table
 *
 * @category Application
 * @package  Auth
 *
 * @author   Anton Shevchuk
 * @created  12.07.11 15:28
 */
class Table extends \Bluz\Db\Table
{

    const TYPE_REQUEST = 'request';
    const TYPE_ACCESS = 'access';

    const PROVIDER_EQUALS = 'equals';
    const PROVIDER_LDAP = 'ldap';
    const PROVIDER_TWITTER = 'twitter';
    const PROVIDER_FACEBOOK = 'facebook';

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
     * @todo foreign key for equals provider is equal to user login -_- ?
     * @param $provider
     * @param $foreignKey
     * @return Row
     */
    public function getAuthRow($provider, $foreignKey)
    {
        return self::findRow(['provider' => $provider, 'foreignKey' => $foreignKey]);
    }

    /**
     * check user by login/pass
     *
     * @param string $username
     * @param string $password
     * @throws Exception
     * @throws AuthException
     * @return Row
     */
    public function checkEquals($username, $password)
    {
        $authRow = $this->getAuthRow(self::PROVIDER_EQUALS, $username);

        if (!$authRow) {
            throw new AuthException("User not found");
        }

        /** @var \Bluz\Auth\Auth $auth */
        $auth = app()->getAuth();
        $options = $auth->getOption(self::PROVIDER_EQUALS);

        if (!isset($options['encryptFunction']) or
            !is_callable($options['encryptFunction'])
        ) {
            throw new Exception("Encryption function for 'equals' adapter is not callable");
        }

        // encrypt password
        $password = call_user_func($options['encryptFunction'], $password, $authRow->tokenSecret);

        if ($password != $authRow->token) {
            throw new AuthException("Wrong password");
        }

        // get auth row
        return $authRow;
    }

    /**
     * authenticate user by login/pass
     *
     * @param string $username
     * @param string $password
     * @throws Exception
     * @throws AuthException
     * @return boolean
     */
    public function authenticateEquals($username, $password)
    {
        /** @var $user Users\Row */
        $authRow = $this->checkEquals($username, $password);

        // get user profile
        $user = Users\Table::findRow($authRow->userId);

        // try to login
        $user->login();

        return true;
    }

    /**
     * authenticate user by login/pass
     *
     * @param Users\Row $user
     * @param string $password
     * @throws Exception
     * @throws AuthException
     * @return boolean
     */
    public function generateEquals($user, $password)
    {
        /** @var \Bluz\Auth\Auth $auth */
        $auth = app()->getAuth();
        $options = $auth->getOption(self::PROVIDER_EQUALS);

        if (!isset($options['encryptFunction']) or
            !is_callable($options['encryptFunction'])
        ) {
            throw new Exception("Encryption function for 'equals' adapter is not callable");
        }

        // clear previous generated Auth record
        // works with change password
        $this->delete(
            [
                'userId' => $user->id,
                'foreignKey' => $user->login,
                'provider' => self::PROVIDER_EQUALS,
                'tokenType' => self::TYPE_ACCESS
            ]
        );

        // new auth row
        $row = new Row();
        $row->userId = $user->id;
        $row->foreignKey = $user->login;
        $row->provider = self::PROVIDER_EQUALS;
        $row->tokenType = self::TYPE_ACCESS;

        // generate secret
        $alpha = range('a', 'z');
        shuffle($alpha);
        $secret = array_slice($alpha, 0, rand(5, 15));
        $secret = md5($user->id . join('', $secret));
        $row->tokenSecret = $secret;

        // encrypt password and save as token
        $row->token = call_user_func($options['encryptFunction'], $password, $secret);

        $row->save();

        return $row;
    }
}
