<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Users;

use Application\Privileges;
use Application\Roles;
use Bluz\Auth\AbstractIdentity;
use Bluz\Validator\Traits\Validator;

/**
 * User
 *
 * @package  Application\Users
 *
 * @property integer $id
 * @property string  $login
 * @property string  $email
 * @property string  $created
 * @property string  $updated
 * @property string  $status
 *
 * @OA\Schema(schema="user", title="user", required={"id", "login", "status"})
 * @OA\Property(property="id", type="integer", description="User UID", example=2)
 * @OA\Property(property="login", type="string", description="Login", example="admin")
 * @OA\Property(property="email", type="string", description="Email", example="admin@domain.com")
 * @OA\Property(property="created", type="string", format="date-time", example="2017-01-01 20:17:01")
 * @OA\Property(property="updated", type="string", format="date-time", example="2017-01-01 20:17:01")
 * @OA\Property(property="status", type="string", enum={"pending", "active", "disabled", "deleted"})
 */
class Row extends AbstractIdentity
{
    use Validator;

    /**
     * Small cache of user privileges
     *
     * @var array
     */
    protected $privileges;

    /**
     * {@inheritdoc}
     *
     * @throws \Bluz\Validator\Exception\ComponentException
     * @throws \Bluz\Db\Exception\TableNotFoundException
     */
    public function beforeSave(): void
    {
        $this->email = strtolower($this->email ?? '');

        $this->addValidator('login')
            ->required()
            ->latin()
            ->length(3, 255)
            ->callback(
                function ($login) {
                    $selector = static::getTable()
                        ::select()
                        ->where('login = ?', $login);

                    if ($this->id) {
                        $selector->andWhere('id != ?', $this->id);
                    }

                    $user = $selector->execute();
                    return !$user;
                },
                'User with this login is already exists'
            );

        $this->addValidator('email')
            ->required()
            ->email(true)
            ->callback(
                function ($email) {
                    $selector = static::getTable()
                        ::select()
                        ->where('email = ?', $email);

                    if ($this->id) {
                        $selector->andWhere('id != ?', $this->id);
                    }

                    $user = $selector->execute();
                    return !$user;
                },
                'User with this email is already exists'
            );
    }

    /**
     * @return void
     */
    public function beforeInsert(): void
    {
        $this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * @return void
     */
    public function beforeUpdate(): void
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }

    /**
     * Get user roles
     */
    public function getRoles()
    {
        return Roles\Table::getInstance()->getUserRoles($this->id);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrivileges(): array
    {
        if (!$this->privileges) {
            $this->privileges = Privileges\Table::getInstance()->getUserPrivileges($this->id);
        }
        return $this->privileges;
    }

    /**
     * Check user role
     *
     * @param integer $roleId
     *
     * @return boolean
     */
    public function hasRole($roleId): bool
    {
        $roles = Roles\Table::getInstance()->getUserRolesIdentity($this->id);

        return in_array($roleId, $roles, false);
    }
}
