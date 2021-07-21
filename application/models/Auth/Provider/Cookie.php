<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth\Provider;

use Application\Auth\Row;
use Application\Auth\Table;
use Application\Users\Row as User;
use Bluz\Auth\AuthException;
use Bluz\Db\Exception\DbException;
use Bluz\Db\Exception\InvalidPrimaryKeyException;
use Bluz\Db\Exception\TableNotFoundException;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Response;
use Exception;

/**
 * Cookie Provider
 *
 * @package  Application\Auth\Provider
 */
class Cookie extends AbstractToken
{
    public const PROVIDER = Table::PROVIDER_COOKIE;

    /**
     * {@inheritdoc}
     *
     * @return Row
     * @throws AuthException
     * @throws DbException
     */
    protected static function verify(?Row $authRow): void
    {
        if (!$authRow) {
            throw new AuthException('User can\'t login with cookies');
        }

        if (strtotime($authRow->expired) < time()) {
            self::remove($authRow->userId);
            throw new AuthException('Token has expired');
        }

        if ($authRow->token !== hash('md5', $authRow->token . $authRow->tokenSecret)) {
            throw new AuthException('Incorrect token');
        }
    }


    /**
     * {@inheritdoc}
     *
     * @return Row
     * @throws DbException
     * @throws InvalidPrimaryKeyException
     * @throws TableNotFoundException
     * @throws Exception
     */
    public static function create(User $user): Row
    {
        // remove old Auth record
        self::remove($user->id);

        $ttl = Auth::getInstance()->getOption('cookie', 'ttl');

        // create new auth row
        $authRow = new Row();

        $authRow->userId = $user->id;
        $authRow->foreignKey = $user->login;
        $authRow->provider = self::PROVIDER;
        $authRow->tokenType = Table::TYPE_ACCESS;
        $authRow->expired = gmdate('Y-m-d H:i:s', time() + $ttl);
        // generate secret part is not required
        // encrypt password and save as token
        $authRow->token = bin2hex(random_bytes(32));

        $authRow->save();

        // Not great, not terrible
        Response::setCookie('Auth-Token', $authRow->token, time() + $ttl);

        return $authRow;
    }
}
