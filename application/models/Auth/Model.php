<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth;

use Application\Exception;
use Bluz\Application;
use Bluz\Proxy\Auth;

/**
 * Auth Model
 *
 * @package  Application\Auth
 */
class Model
{
    /**
     * Call Hash Function
     *
     * @param string $password
     *
     * @return string
     * @throws Exception
     */
    public static function hash(string $password): string
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
     * @return bool
     * @throws Exception
     */
    public static function verify(string $password, string $hash): bool
    {
        $verify = Auth::getInstance()->getOption('verify');

        if (!$verify || !is_callable($verify)) {
            throw new Exception('Verify function for `Auth` package is not callable');
        }

        // verify password with hash
        return $verify($password, $hash);
    }
}
