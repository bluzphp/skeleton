<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Auth;

use Application\Exception;
use Application\Users;
use Bluz\Application;
use Bluz\Auth\AbstractTable;
use Bluz\Auth\AuthException;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Config;
use Bluz\Proxy\Response;

/**
 * Auth Table
 *
 * @package  Application\Auth
 *
 * @method   static Row findRow($primaryKey)
 * @method   static Row findRowWhere($whereList)
 *
 * @author   Anton Shevchuk
 * @created  12.07.11 15:28
 */
class Table extends AbstractTable
{
    /**
     * Time that the token remains valid
     */
    const TOKEN_EXPIRATION_TIME = 1800;

    /**
     * Authenticate user by login/pass
     *
     * @param string $username
     * @param string $password
     * @throws AuthException
     * @throws Exception
     */
    public function authenticateEquals($username, $password)
    {
        $authRow = $this->checkEquals($username, $password);

        // get user profile
        if (!$user = Users\Table::findRow($authRow->userId)) {
            throw new Exception("User is undefined in system");
        }

        // try to login
        $user->tryLogin();
    }

    /**
     * Check user by login/pass
     *
     * @param string $username
     * @param string $password
     * @throws AuthException
     * @return Row
     */
    public function checkEquals($username, $password)
    {
        $authRow = $this->getAuthRow(self::PROVIDER_EQUALS, $username);

        if (!$authRow) {
            throw new AuthException("User not found");
        }

        // verify password
        if (!$this->callVerifyFunction($password, $authRow->token)) {
            throw new AuthException("Wrong password");
        }

        // get auth row
        return $authRow;
    }

    /**
     * Authenticate user by login/pass
     *
     * @param Users\Row $user
     * @param string $password
     * @throws AuthException
     * @return Row
     */
    public function generateEquals($user, $password)
    {
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

        // generate secret part is not required
        // encrypt password and save as token
        $row->token = $this->callHashFunction($password);

        $row->save();

        return $row;
    }

    /**
     * Generates cookie for authentication
     *
     * @throws \Bluz\Db\Exception\DbException
     */
    public function generateCookie()
    {
        $hash = hash('md5', microtime(true));
        $ttl = Config::getModuleData('users', 'rememberMe');

        $this->delete(
            [
                'userId' => Auth::getIdentity()->id,
                'foreignKey' => Auth::getIdentity()->login,
                'provider' => self::PROVIDER_COOKIE,
                'tokenType' => self::TYPE_ACCESS,
            ]
        );

        $row = new Row();
        $row->userId = Auth::getIdentity()->id;
        $row->foreignKey = Auth::getIdentity()->login;
        $row->provider = self::PROVIDER_COOKIE;
        $row->tokenType = self::TYPE_ACCESS;
        $row->expired = gmdate('Y-m-d H:i:s', time() + $ttl);

        $row->tokenSecret = $this->generateSecret(Auth::getIdentity()->id);
        $row->token = hash('md5', $row->tokenSecret . $hash);

        $row->save();

        Response::setCookie('rToken', $hash, time() + $ttl, '/');
        Response::setCookie('rId', Auth::getIdentity()->id, time() + $ttl, '/');
    }

    /**
     * Authenticate via cookie
     *
     * @param $userId
     * @param $token
     * @throws AuthException
     * @throws Exception
     */
    public function authenticateCookie($userId, $token)
    {
        $authRow = $this->checkCookie($userId, $token);

        // get user row
        $user = Users\Table::findRow($authRow->userId);

        // try to login
        $user->tryLogin();
    }

    /**
     * Check if supplied cookie is valid
     *
     * @param $userId
     * @param $token
     * @return Row
     * @throws AuthException
     */
    public function checkCookie($userId, $token)
    {
        if (!$authRow = $this->findRowWhere(['userId' => $userId, 'provider' => self::PROVIDER_COOKIE])) {
            throw new AuthException('User not found');
        }

        if (strtotime($authRow->expired) < time()) {
            $this->removeCookieToken($userId);
            throw new AuthException('Token has expired');
        }

        if ($authRow->token != hash('md5', $authRow->tokenSecret . $token)) {
            throw new AuthException('Incorrect token');
        }

        return $authRow;
    }

    /**
     * Removes a cookie-token from database
     *
     * @param $userId
     * @throws \Bluz\Db\Exception\DbException
     */
    public function removeCookieToken($userId)
    {
        $this->delete(
            [
                'userId' => $userId,
                'provider' => self::PROVIDER_COOKIE
            ]
        );
    }

    /**
     * Call Hash Function
     *
     * @param string $password
     * @throws \Application\Exception
     * @return string
     */
    protected function callHashFunction($password)
    {
        /** @var \Bluz\Auth\Auth $auth */
        $auth = Auth::getInstance();
        $options = $auth->getOption(self::PROVIDER_EQUALS);

        if (!isset($options['hash']) or
            !is_callable($options['hash'])
        ) {
            throw new Exception("Hash function for 'equals' adapter is not callable");
        }

        // encrypt password with secret
        return call_user_func($options['hash'], $password);
    }

    /**
     * Call Verify Function
     *
     * @param string $password
     * @param string $hash
     * @throws \Application\Exception
     * @return string
     */
    protected function callVerifyFunction($password, $hash)
    {
        /** @var \Bluz\Auth\Auth $auth */
        $auth = Auth::getInstance();
        $options = $auth->getOption(self::PROVIDER_EQUALS);

        if (!isset($options['verify']) or
            !is_callable($options['verify'])
        ) {
            throw new Exception("Verify function for 'equals' adapter is not callable");
        }

        // verify password with hash
        return call_user_func($options['verify'], $password, $hash);
    }

    /**
     * authenticate user by token
     *
     * @param string $token
     * @throws \Bluz\Auth\AuthException
     * @return void
     */
    public function authenticateToken($token)
    {
        $authRow = $this->checkToken($token);

        // get user profile
        $user = Users\Table::findRow($authRow->userId);

        // try to login
        $user->tryLogin();
    }

    /**
     * authenticate user by token
     *
     * @param string $token
     * @throws \Bluz\Auth\AuthException
     * @return Row
     */
    public function checkToken($token)
    {
        if (!$authRow = $this->findRowWhere(['token' =>  $token, 'provider' => self::PROVIDER_TOKEN])) {
            throw new AuthException('Invalid token');
        }

        if ($authRow->expired < gmdate('Y-m-d H:i:s')) {
            throw new AuthException('Token has expired');
        }

        return $authRow;
    }

    /**
     * @param $equalAuth
     * @return Row
     * @throws Exception
     * @throws \Bluz\Db\Exception\DbException
     */
    public function generateToken($equalAuth)
    {
        // clear previous generated Auth record
        // works with change password
        $this->delete(
            [
                'userId' => $equalAuth->userId,
                'foreignKey' => $equalAuth->foreignKey,
                'provider' => self::PROVIDER_TOKEN,
                'tokenType' => self::TYPE_ACCESS,
            ]
        );
        // new auth row
        $row = new Row();
        $row->userId = $equalAuth->userId;
        $row->foreignKey = $equalAuth->foreignKey;
        $row->provider = self::PROVIDER_TOKEN;
        $row->tokenType = self::TYPE_ACCESS;
        $row->expired = gmdate('Y-m-d H:i:s', time() + self::TOKEN_EXPIRATION_TIME);

        // generate secret
        $row->tokenSecret = $this->generateSecret($equalAuth->userId);

        // encrypt password and save as token
        $row->token = $this->callHashFunction($equalAuth->token);

        $row->save();

        return $row;
    }
}
