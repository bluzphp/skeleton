<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth\Provider;

use Application\Auth\Model;
use Application\Auth\Row;
use Application\Auth\Table;
use Application\Users\Row as User;
use Bluz\Auth\AuthException;
use Bluz\Db\Exception\DbException;
use Bluz\Db\Exception\InvalidPrimaryKeyException;
use Bluz\Db\Exception\TableNotFoundException;

/**
 * Equals Provider
 *
 * @package  Application\Auth\Provider
 */
class Equals extends AbstractProvider
{
    public const PROVIDER = Table::PROVIDER_EQUALS;

    /**
     * {@inheritdoc}
     *
     * @throws AuthException
     * @throws DbException
     * @throws InvalidPrimaryKeyException
     */
    public static function authenticate(string $login, string $password = null): Row
    {
        $authRow = self::find($login);

        self::verify($authRow, $password);

        self::login($authRow);

        return $authRow;
    }


    /**
     * {@inheritdoc}
     *
     * @return Row
     * @throws DbException
     * @throws AuthException
     */
    public static function find(string $login): ?Row
    {
        return Table::findRowWhere(['foreignKey' => $login, 'provider' => self::PROVIDER]);
    }

    /**
     * {@inheritdoc}
     *
     * @param Row|null $authRow
     * @return void
     * @throws AuthException
     */
    protected static function verify(?Row $authRow, string $password = null): void
    {
        if (!$authRow) {
            throw new AuthException('User can\'t login with password');
        }

        // verify password
        if (!Model::verify($password, $authRow->token)) {
            throw new AuthException('Wrong password');
        }
    }

    /**
     * @param User $user
     * @param string $password
     * @return Row
     * @throws DbException
     * @throws InvalidPrimaryKeyException
     * @throws TableNotFoundException
     */
    public static function create(User $user, string $password = ''): Row
    {
        // remove old Auth record
        self::remove($user->id);

        // create new auth row
        $row = new Row();

        $row->userId = $user->id;
        $row->foreignKey = $user->login;
        $row->provider = self::PROVIDER;
        $row->tokenType = Table::TYPE_ACCESS;
        // generate secret part is not required
        // encrypt password and save as token
        $row->token = Model::hash($password);

        $row->save();

        return $row;
    }
}
