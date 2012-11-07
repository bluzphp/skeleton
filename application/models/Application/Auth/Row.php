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

/**
 * @category Application
 * @package  Auth
 *
 * @author   Anton Shevchuk
 * @created  24.10.12 11:57
 */
class Row extends \Bluz\Db\Row
{
    const TYPE_REQUEST = 'request';
    const TYPE_ACCESS = 'access';

    const PROVIDER_EQUALS = 'equals';
    const PROVIDER_LDAP = 'ldap';
    const PROVIDER_TWITTER = 'twitter';
    const PROVIDER_FACEBOOK = 'facebook';

    /**
     * Foreign key to users table
     * @var integer
     */
    public $userId;
    public $provider;
    public $foreignKey;
    public $token;
    public $tokenSecret;
    public $tokenType;
    public $created;
    public $updated;

    /**
     * __insert
     *
     * @return void
     */
    public function preInsert()
    {
        $this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * __update
     *
     * @return void
     */
    public function preUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}
