<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Roles;

use Bluz\Proxy\Cache;
use Bluz\Proxy\Db;

/**
 * Table of User Roles
 *
 * @package  Application\Roles
 *
 * @method   static Row|null findRow($primaryKey)
 * @see      \Bluz\Db\Table::findRow()
 * @method   static Row|null findRowWhere($whereList)
 * @see      \Bluz\Db\Table::findRowWhere()
 */
class Table extends \Bluz\Db\Table
{
    public const BASIC_ADMIN = 'admin';
    public const BASIC_GUEST = 'guest';
    public const BASIC_MEMBER = 'member';
    public const BASIC_SYSTEM = 'system';

    /**
     * Table
     *
     * @var string
     */
    protected $name = 'acl_roles';

    /**
     * Primary key(s)
     *
     * @var array
     */
    protected $primary = ['id'];

    /**
     * @var array
     */
    protected $basicRoles = [
        self::BASIC_ADMIN,
        self::BASIC_GUEST,
        self::BASIC_MEMBER,
        self::BASIC_SYSTEM
    ];

    /**
     * Init table relations
     *
     * @return void
     */
    public function init(): void
    {
        $this->linkTo('id', 'UsersRoles', 'roleId');
        $this->linkToMany('Users', 'UsersRoles');
    }

    /**
     * Get all basic roles
     *
     * @return array
     */
    public function getBasicRoles(): array
    {
        return $this->basicRoles;
    }

    /**
     * Get all roles in system
     *
     * @return Row[]
     */
    public function getRoles(): array
    {
        return self::fetch('SELECT * FROM acl_roles ORDER BY id');
    }

    /**
     * Get all user roles in system
     *
     * @param integer $userId
     *
     * @return Row[]
     */
    public function getUserRoles($userId): array
    {
        $data = self::fetch(
            'SELECT r.*
            FROM acl_roles AS r, acl_users_roles AS u2r
            WHERE r.id = u2r.roleId AND u2r.userId = ?',
            [$userId]
        );
        return $data;
    }

    /**
     * Get all user roles in system
     *
     * @param integer $userId
     *
     * @return array of identity
     */
    public function getUserRolesIdentity($userId): array
    {
        $cacheKey = 'users.roles.' . $userId;
        if (!$data = Cache::get($cacheKey)) {
            $data = Db::fetchColumn(
                'SELECT r.id
                FROM acl_roles AS r, acl_users_roles AS u2r
                WHERE r.id = u2r.roleId AND u2r.userId = ?
                ORDER BY r.id ASC',
                [$userId]
            );
            Cache::set($cacheKey, $data, Cache::TTL_NO_EXPIRY, ['system', 'users', 'roles']);
        }
        return $data;
    }
}
