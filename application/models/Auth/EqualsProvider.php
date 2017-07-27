<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth;

use Application\Exception;
use Application\Users\Row as UsersRow;
use Application\Users\Table as UsersTable;
use Bluz\Auth\AuthException;

/**
 * EqualsProvider
 *
 * @package  Application\Auth
 * @author   Anton Shevchuk
 */
class EqualsProvider
{
    /**
     * Authenticate user by login/pass
     *
     * @param string $username
     * @param string $password
     *
     * @throws AuthException
     * @throws Exception
     */
    public static function authenticate($username, $password)
    {
        $authRow = self::verify($username, $password);
        $user = UsersTable::findRow($authRow->userId);

        // try to login
        Table::tryLogin($user);
    }

    /**
     * Get Auth record by login/pass
     *
     * @param string $username
     * @param string $password
     *
     * @return Row
     * @throws AuthException
     * @throws Exception
     */
    public static function verify($username, $password) : Row
    {
        /* @var Row $authRow */
        $authRow = Table::findRowWhere(['foreignKey' => $username, 'provider' => Table::PROVIDER_EQUALS]);

        if (!$authRow) {
            throw new AuthException('User can\'t login with password');
        }

        // verify password
        if (!Table::verify($password, $authRow->token)) {
            throw new AuthException('Wrong password');
        }

        return $authRow;
    }

    /**
     * Create new Auth record for user
     *
     * @param UsersRow $user
     * @param string   $password
     *
     * @return Row
     * @throws AuthException
     * @throws Exception
     */
    public static function create($user, $password)
    {
        // remove old Auth record
        self::remove($user->id);

        // create new auth row
        $row = new Row();
        $row->userId = $user->id;
        $row->foreignKey = $user->login;
        $row->provider = Table::PROVIDER_EQUALS;
        $row->tokenType = Table::TYPE_ACCESS;

        // generate secret part is not required
        // encrypt password and save as token
        $row->token = Table::hash($password);
        $row->save();

        return $row;
    }

    /**
     * Remove Auth record
     *
     * @param integer $id
     *
     * @return void
     */
    public static function remove($id)
    {
        // clear previous generated Auth record
        // works with change password
        Table::delete(
            [
                'userId' => $id,
                'provider' => Table::PROVIDER_EQUALS,
                'tokenType' => Table::TYPE_ACCESS
            ]
        );
    }
}
