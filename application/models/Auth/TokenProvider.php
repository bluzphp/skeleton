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
use Bluz\Proxy\Auth;

/**
 * TokenProvider
 *
 * @package  Application\Auth
 * @author   Anton Shevchuk
 */
class TokenProvider
{
    /**
     * authenticate user by token
     *
     * @param string $token
     *
     * @throws \Bluz\Auth\AuthException
     * @throws \Application\Exception
     */
    public static function authenticate($token)
    {
        $authRow = self::verify($token);
        $user = UsersTable::findRow($authRow->userId);

        // try to login
        $user->tryLogin();
    }

    /**
     * authenticate user by token
     *
     * @param string $token
     *
     * @throws \Bluz\Auth\AuthException
     * @return Row
     */
    public static function verify($token)
    {
        if (!$authRow = Table::findRowWhere(['token' => $token, 'provider' => Table::PROVIDER_TOKEN])) {
            throw new AuthException('Invalid token');
        }

        if ($authRow->expired < gmdate('Y-m-d H:i:s')) {
            throw new AuthException('Token has expired');
        }

        return $authRow;
    }

    /**
     * Create new Auth record for user
     *
     * @param UsersRow $user
     *
     * @return Row
     * @throws Exception
     */
    public static function create($user)
    {
        // clear previous generated Auth record
        self::remove($user->id);

        $ttl = Auth::getInstance()->getOption('token', 'ttl');

        // new auth row
        $row = new Row();
        $row->userId = $user->id;
        $row->foreignKey = $user->login;
        $row->provider = Table::PROVIDER_TOKEN;
        $row->tokenType = Table::TYPE_ACCESS;
        $row->expired = gmdate('Y-m-d H:i:s', time() + $ttl);
        $row->token = bin2hex(random_bytes(32));

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
                'provider' => Table::PROVIDER_TOKEN,
                'tokenType' => Table::TYPE_ACCESS
            ]
        );
    }
}
