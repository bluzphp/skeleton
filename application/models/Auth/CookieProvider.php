<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth;

use Application\Exception;
use Application\Users\Table as UsersTable;
use Bluz\Auth\AuthException;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Response;

/**
 * CookieProvider
 *
 * @package  Application\Auth
 * @author   Anton Shevchuk
 */
class CookieProvider
{
    /**
     * Authenticate user by token
     *
     * @param string $token
     *
     * @throws AuthException
     * @throws Exception
     */
    public static function authenticate($token)
    {
        $authRow = self::verify($token);
        $user = UsersTable::findRow($authRow->userId);

        // try to login
        Table::tryLogin($user);
    }

    /**
     * Check if supplied cookie is valid
     *
     * @param string $token
     *
     * @return Row
     * @throws AuthException
     */
    public static function verify($token)
    {
        /* @var Row $authRow */
        $authRow = Table::findRowWhere(['token' => $token, 'provider' => Table::PROVIDER_COOKIE]);

        if (!$authRow) {
            throw new AuthException('User can\'t login with cookies');
        }

        if (strtotime($authRow->expired) < time()) {
            self::remove($authRow->userId);
            throw new AuthException('Token has expired');
        }

        if ($authRow->token != hash('md5', $token . $authRow->tokenSecret)) {
            throw new AuthException('Incorrect token');
        }

        return $authRow;
    }

    /**
     * Create and save Auth record, and send cookies
     *
     * @return Row
     * @throws AuthException
     * @throws Exception
     */
    public static function create($user)
    {
        // remove old Auth record
        self::remove($user->id);

        $ttl = Auth::getInstance()->getOption('cookie', 'ttl');

        // create new auth row
        $row = new Row();
        $row->userId = $user->id;
        $row->foreignKey = $user->login;
        $row->provider = Table::PROVIDER_COOKIE;
        $row->tokenType = Table::TYPE_ACCESS;
        $row->expired = gmdate('Y-m-d H:i:s', time() + $ttl);

        // generate secret part is not required
        // encrypt password and save as token
        $row->token = bin2hex(random_bytes(32));
        $row->save();

        Response::setCookie('aToken', $row->token, time() + $ttl);

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
                'provider' => Table::PROVIDER_COOKIE,
                'tokenType' => Table::TYPE_ACCESS
            ]
        );
    }
}
