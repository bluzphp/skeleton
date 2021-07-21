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

/**
 * Token Provider
 *
 * @package  Application\Auth\Provider
 */
abstract class AbstractToken extends AbstractProvider
{
    /**
     * {@inheritdoc}
     *
     * @throws AuthException
     * @throws DbException
     * @throws InvalidPrimaryKeyException
     */
    public static function authenticate(string $token): Row
    {
        $authRow = self::find($token);

        self::verify($authRow);

        self::login($authRow);

        return $authRow;
    }

    /**
     * {@inheritdoc}
     *
     * @return Row
     * @throws DbException
     */
    protected static function find(string $token): ?Row
    {
        return Table::findRowWhere(['token' => $token, 'provider' => self::PROVIDER]);
    }

    /**
     * {@inheritdoc}
     *
     * @param Row|null $authRow
     * @return void
     * @throws AuthException
     */
    protected static function verify(?Row $authRow): void
    {
        if (!$authRow) {
            throw new AuthException('Invalid token');
        }

        if ($authRow->expired < gmdate('Y-m-d H:i:s')) {
            throw new AuthException('Token has expired');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return Row
     * @throws DbException
     * @throws InvalidPrimaryKeyException
     * @throws TableNotFoundException
     */
    public static function create(User $user): Row
    {
        // clear previous generated Auth record
        self::remove((int) $user->id);

        $ttl = Auth::getInstance()->getOption('token', 'ttl');

        // new auth row
        $row = new Row();

        $row->userId = $user->id;
        $row->foreignKey = $user->login;
        $row->provider = self::PROVIDER;
        $row->tokenType = Table::TYPE_ACCESS;
        $row->expired = gmdate('Y-m-d H:i:s', time() + $ttl);
        $row->token = bin2hex(random_bytes(32));

        $row->save();

        return $row;
    }
}
