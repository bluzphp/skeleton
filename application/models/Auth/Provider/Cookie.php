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
use Bluz\Proxy\Auth;
use Bluz\Proxy\Response;

/**
 * Cookie Provider
 *
 * @package  Application\Auth
 * @author   Anton Shevchuk
 */
class Cookie extends AbstractProvider
{
    const PROVIDER = Table::PROVIDER_COOKIE;

    public static function authenticate($token) : void
    {
        $authRow = self::verify($token);
        $user = UsersTable::findRow($authRow->userId);

        // try to login
        Table::tryLogin($user);
    }

    public static function verify($token) : Row
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

        if ($authRow->token !== hash('md5', $token . $authRow->tokenSecret)) {
            throw new AuthException('Incorrect token');
        }

        return $authRow;
    }

    public static function create($user) : Row
    {
        // remove old Auth record
        self::remove($user->id);

        $ttl = Auth::getInstance()->getOption('cookie', 'ttl');

        // create new auth row
        $authRow = new Row();
        $authRow->userId = $user->id;
        $authRow->foreignKey = $user->login;
        $authRow->provider = Table::PROVIDER_COOKIE;
        $authRow->tokenType = Table::TYPE_ACCESS;
        $authRow->expired = gmdate('Y-m-d H:i:s', time() + $ttl);

        // generate secret part is not required
        // encrypt password and save as token
        $authRow->token = bin2hex(random_bytes(32));
        $authRow->save();

        Response::setCookie('Auth-Token', $authRow->token, time() + $ttl);

        return $authRow;
    }
}
