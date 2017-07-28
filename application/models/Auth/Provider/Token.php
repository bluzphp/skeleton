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

/**
 * Token Provider
 *
 * @package  Application\Auth
 * @author   Anton Shevchuk
 */
class Token extends AbstractProvider
{
    const PROVIDER = Table::PROVIDER_TOKEN;

    public static function authenticate($token)
    {
        $authRow = self::verify($token);
        $user = UsersTable::findRow($authRow->userId);

        // try to login
        $user->tryLogin();
    }

    public static function verify($token) : Row
    {
        if (!$authRow = Table::findRowWhere(['token' => $token, 'provider' => self::PROVIDER])) {
            throw new AuthException('Invalid token');
        }

        if ($authRow->expired < gmdate('Y-m-d H:i:s')) {
            throw new AuthException('Token has expired');
        }

        return $authRow;
    }

    public static function create($user) : Row
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
}
