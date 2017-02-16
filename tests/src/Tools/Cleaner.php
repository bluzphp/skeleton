<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Tools;

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
     * @param string $dir
     * @return bool
     */
    public static function delete($dir)
    {
        system('rm -rf ' . escapeshellarg($dir), $result);
        return $result == 0; // UNIX commands return zero on success
    }
}
