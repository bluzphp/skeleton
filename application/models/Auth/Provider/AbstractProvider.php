<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth\Provider;

use \Application\Auth\Row;
use \Application\Auth\Table;
use \Application\Users\Row as User;

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
     *
     * @return void
     * @throws \Application\Exception
     * @throws \Bluz\Auth\AuthException
     */
    abstract public static function authenticate($token): void;

    /**
     * Check if supplied cookie is valid
     *
     * @param string $token
     *
     * @return Row
     * @throws \Bluz\Auth\AuthException
     */
    abstract public static function verify($token): Row;

    /**
     * Create and save Auth record, and send cookies
     *
     * @param User $user
     *
     * @return Row
     * @throws \Application\Exception
     * @throws \Bluz\Auth\AuthException
     */
    abstract public static function create($user): Row;

    /**
     * Remove Auth record by user ID
     *
     * @param integer $id User ID
     *
     * @return void
     * @throws \Bluz\Db\Exception\DbException
     */
    public static function remove($id): void
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
