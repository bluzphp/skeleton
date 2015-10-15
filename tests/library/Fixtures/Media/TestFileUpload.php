<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Application\Tests\Fixtures\Media;

use Bluz\Http\FileUpload;

/**
 * TestFileUpload
 *
 * @package  Application\Tests
 *
 * @author   Taras Seryogin
 * @created  02.06.14 13:20
 */
class TestFileUpload extends FileUpload
{
    /**
     * __construct
     *
     * @param array $array The array of $_FILES
     */
    public function __construct($array = null)
    {
        $rawFiles = $array ? : $_FILES;
        foreach ($rawFiles as $key => $file) {
            $this->processFileArray($key, $file);
        }
    }

    /**
     * Create File
     *
     * @param array $fileInfo
     * @param string $fileKey
     * @param string ...$keys
     * @return TestFile instance
     */
    public function createFile($fileInfo, $fileKey, ...$keys)
    {
        $testFile = new TestFile($fileInfo);
        $this->files[$fileKey][] = $testFile;
        return $testFile;
    }
}
