<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth;

use Application\Exception;
use Application\Users;
use Bluz\Application;

use Bluz\Auth\AuthException;
use Bluz\Auth\Model\AbstractTable;
use Bluz\Proxy\Auth;

/**
 * Auth Table
 *
 * @package  Application\Auth
 *
 * @method   static Row findRow($primaryKey)
 * @see      \Bluz\Db\Table::findRow()
 * @method   static Row findRowWhere($whereList)
 * @see      \Bluz\Db\Table::findRowWhere()
 *
 * @author   Anton Shevchuk
 * @created  12.07.11 15:28
 */
class Table extends AbstractTable
{
    /**
     * Call Hash Function
     *
     * @param string $password
     *
     * @throws \Application\Exception
     * @return string
     */
    public static function hash($password): string
    {
        $hash = Auth::getInstance()->getOption('hash');

        if (!$hash || !is_callable($hash)) {
            throw new Exception('Hash function for `Auth` package is not callable');
        }

        // encrypt password with secret
        return $hash($password);
    }

    /**
     * Call Verify Function
     *
     * @param string $password
     * @param string $hash
     *
     * @throws \Application\Exception
     * @return bool
     */
    public static function verify($password, $hash): bool
    {
        $verify = Auth::getInstance()->getOption('verify');

        if (!$verify || !is_callable($verify)) {
            throw new Exception('Verify function for `Auth` package is not callable');
        }

        // verify password with hash
        return $verify($password, $hash);
    }

    /**
     * Can entity login
     *
     * @param $user
     *
     * @throws AuthException
     */
    public static function tryLogin($user): void
    {
        switch ($user->status) {
            case (Users\Table::STATUS_PENDING):
                throw new AuthException('Your account is pending activation', 403);
            case (Users\Table::STATUS_DISABLED):
                throw new AuthException('Your account is disabled by administrator', 403);
            case (Users\Table::STATUS_ACTIVE):
                // save user to new session
                Auth::setIdentity($user);
                break;
            default:
                throw new AuthException('User not found');
        }
    }
}
