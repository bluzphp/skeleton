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
use Bluz\Auth\AuthException;

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
     * @param string $provider
     * @param string $foreignKey
     * @return Row
     */
    public function getAuthRow($provider, $foreignKey)
    {
        return self::findRow(['provider' => $provider, 'foreignKey' => $foreignKey]);
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

        // encrypt password
        $encrypt = $this->callEncryptFunction($password, $authRow->tokenSecret);

        if ($encrypt != $authRow->token) {
            throw new AuthException("Wrong password");
        }

        // get auth row
        return $authRow;
    }

    /**
     * authenticate user by login/pass
     *
     * @param Users\Row $user
     * @param string $password
     * @throws Exception
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

        // generate secret
        $alpha = range('a', 'z');
        shuffle($alpha);
        $secret = array_slice($alpha, 0, rand(5, 15));
        $secret = md5($user->id . join('', $secret));
        $row->tokenSecret = $secret;

        // encrypt password and save as token
        $row->token = $this->callEncryptFunction($password, $secret);

        $row->save();

        return $row;
    }

    /**
     * callEncryptFunction
     *
     * @param string $password
     * @param string $secret
     * @throws \Application\Exception
     * @return string
     */
    protected function callEncryptFunction($password, $secret)
    {
        /** @var \Bluz\Auth\Auth $auth */
        $auth = app()->getAuth();
        $options = $auth->getOption(self::PROVIDER_EQUALS);

        if (!isset($options['encryptFunction']) or
            !is_callable($options['encryptFunction'])
        ) {
            throw new Exception("Encryption function for 'equals' adapter is not callable");
        }

        // encrypt password with secret
        return call_user_func($options['encryptFunction'], $password, $secret);
    }
}
