<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Utils;

use Application\Exception;

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
    public static function delete($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new Exception("$dirPath must be a directory");
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::delete($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}
