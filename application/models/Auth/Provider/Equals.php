<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth\Provider;

use Application\Auth\Row;
use Application\Auth\Table;
use Application\Users\Table as UsersTable;
use Bluz\Auth\AuthException;

/**
 * Equals Provider
 *
 * @package  Application\Auth
 * @author   Anton Shevchuk
 */
class Equals extends AbstractProvider
{
    const PROVIDER = Table::PROVIDER_EQUALS;

    public static function authenticate($username, $password = '')
    {
        $authRow = self::verify($username, $password);
        $user = UsersTable::findRow($authRow->userId);

        // try to login
        Table::tryLogin($user);
    }

    public static function verify($username, $password = '') : Row
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

    public static function create($user, $password = '') : Row
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
}
