<?php
/**
 * Copyright (c) 2011 by Bluz PHP Team
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

namespace Bluz\Ldap;
use Bluz\Exception;
use Bluz\Options;

/**
 * LDAP Connection class. Use to connect to LDAP and make search.
 *
 * @category Bluz
 * @package Ldap
 */
class Connector implements LdapIterator
{
    /**
     * Connection resource
     *
     * @var resource
     */
    protected $_connection = null;
    /**
     * Hostname of LDAP server
     *
     * @var string
     */
    protected $_host = null;
    /**
     * Domain for LDAP server
     *
     * @var string
     */
    protected $_domain = null;
    /**
     * search baseDn for LDAP server
     *
     * @var string
     */
    protected $_baseDn = null;
    /**
     * Is connection binded
     *
     * @var unknown_type
     */
    protected $_binded = false;
    /**
     * Search result resource
     *
     * @var resource
     */
    protected $_resource = null;

    /**
     * Create instance
     *
     * @param null $options
     * @return \Bluz\Ldap\Connector
     *
     * @internal param string $host
     */
    public function __construct($options = null)
    {
        if (!is_null($options)) {
            if (!isset($options['host'])) {
                throw new LdapException('LDAP connection can\'t be initialized: required host');
            }
            Options::setConstructorOptions($this, $options);
            $this->connect();
        }
    }

    /**
     * set Host param
     *
     * @param string $host
     * @return \Bluz\Ldap\Connector
     */
    public function setHost($host)
    {
        if (empty($host)) {
            throw new LdapException('LDAP connection can\'t be initialized: required host value');
        }
        $this->_host = $host;
        return $this;
    }

    /**
     * set Host param
     *
     * @param $domain
     * @return \Bluz\Ldap\Connector
     *
     * @internal param string $host
     */
    public function setDomain($domain)
    {
        if (!empty($domain)) {
            $this->_domain = $domain;
        }
        return $this;
    }

    /**
     * set baseDn param for search attr
     *
     * @param string $baseDn
     * @return \Bluz\Ldap\Connector
     */
    public function setBaseDn($baseDn)
    {
        if (empty($baseDn)) {
            throw new LdapException('LDAP Search can\'t be initialized without "baseDn" search params');
        }
        $this->_baseDn = $baseDn;
        return $this;
    }

    /**
     * Unlink in desctuctor
     *
     */
    public function __destruct()
    {
        if ($this->_binded) {
            ldap_unbind($this->_connection);
        }
    }

    /**
     * Connect to LDAP server
     *
     */
    public function connect()
    {
        if (!($this->_connection = ldap_connect($this->_host))) {
            throw new LdapException("Can't connect to " . $this->_host);
        }
    }

    /**
     * Bind username and pass on server
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function bind($username = null, $password = null)
    {
        $this->_binded = false;
        if ($username && $password) {
            $username = $username . ( ($this->_domain) ? ("@" . $this->_domain) : ("") );
            $this->_binded = @ldap_bind($this->_connection, $username, $password);
            $errorMsg = "Can't connect with " . $username . "/"
                    . str_repeat("*", strlen($password)) . " on " . $this->_host;
        } else {
            $this->_binded = @ldap_bind($this->_connection);
            $errorMsg = "Can't connect anonymously on " . $this->_host;
        }
        $errors = ldap_errno($this->_connection);
        if ($errors != 0x00) {
            throw new LdapException($errorMsg);
        }
        return $this->_binded;
    }

    /**
     * Do search. N.B!: Results are not returning.
     * Use getSearch** methods to get results.
     *
     * @param string $filter
     * @param string $attributes
     * @return void
     *
     * @internal param string $this ->_baseDn
     */
    public function doSearch($filter, $attributes = null)
    {
        if (!$this->_binded) {
            throw new LdapException("Can't do search. Not binded.");
        }
        if (!is_null($attributes)) {
            $this->_resource = @ldap_search(
                $this->_connection, $this->_baseDn, $filter, $attributes
            );
        } else {
            @$this->_resource =
                ldap_search($this->_connection, $this->_baseDn, $filter);
        }

        if (!$this->_resource) {
            throw new LdapException("Search error: '" . ldap_error($this->_connection) . "'");
        }
    }

    /**
     * Return search entries
     *
     * @return Entries\Entries
     */
    public function getSearchEntries()
    {
        if (!$this->_resource) {
            return false;
        }
        return new Entries\Entries(ldap_get_entries($this->_connection, $this->_resource));
    }
}