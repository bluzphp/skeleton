<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth\Provider;

use Application\Auth\Row;
use Application\Auth\Table;
use Application\Exception;
use Application\Users\Row as User;
use Application\Users\Table as Users;
use Bluz\Auth\AuthException;
use Bluz\Db\Exception\DbException;
use Bluz\Db\Exception\InvalidPrimaryKeyException;
use Bluz\Proxy\Auth;

/**
 * AbstractProvider
 *
 * @package  Application\Auth\Provider
 */
abstract class AbstractProvider
{
    public const PROVIDER = 'abstract';

    /**
     * Authenticate user by token
     *
     * @param string $token
     * @return Row
     * @throws Exception
     * @throws AuthException
     */
    abstract public static function authenticate(string $token): Row;

    /**
     * Find AuthRow by token
     *
     * @param string $token
     * @return Row
     */
    abstract protected static function find(string $token): ?Row;

    /**
     * Check if supplied token is valid
     *
     * @param Row|null $row
     * @return void
     * @throws AuthException
     */
    abstract protected static function verify(?Row $row): void;

    /**
     * @param Row $authRow
     * @throws AuthException
     * @throws DbException
     * @throws InvalidPrimaryKeyException
     */
    public static function login(Row $authRow): void
    {
        $user = Users::findRow($authRow->userId);

        switch ($user->status) {
            case (Users::STATUS_PENDING):
                throw new AuthException('Your account is pending activation', 403);
            case (Users::STATUS_DISABLED):
                throw new AuthException('Your account is disabled by administrator', 403);
            case (Users::STATUS_ACTIVE):
                // save user to new session
                Auth::setIdentity($user);
                break;
            case (Users::STATUS_DELETED):
            default:
                throw new AuthException('User not found');
        }
    }

    /**
     * Create and save Auth record
     *
     * @param User $user
     *
     * @return Row
     * @throws Exception
     * @throws AuthException
     */
    abstract public static function create(User $user): Row;

    /**
     * Remove Auth record by user ID
     *
     * @param int|null $id User ID
     *
     * @return void
     * @throws DbException
     */
    public static function remove(?int $id): void
    {
        // clear previous generated Auth record
        // works with change password
        Table::delete(
            [
                'userId' => $id,
                'provider' => static::PROVIDER,
                'tokenType' => Table::TYPE_ACCESS
            ]
        );
    }
}
