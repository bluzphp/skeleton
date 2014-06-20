<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Utils;

/**
 * Utils
 *
 * @category Library
 * @package  Utils
 *
 * @author   Taras Seryogin
 */
class Cleaner
{
    /**
     * Delete photo from file system
     */
    public static function delete($dir)
    {
        system('rm -rf ' . escapeshellarg($dir), $retval);
        return $retval == 0; // UNIX commands return zero on success
    }
}
