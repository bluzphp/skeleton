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
     * createFile
     *
     * @param string $name
     * @param int $error
     * @param string $tmpName
     * @param string $type
     * @param int $size
     * @return TestFile instance
     */
    public function createFile($name, $error, $tmpName, $type, $size)
    {
        return new TestFile(
            [
                'name' => $name,
                'error' => $error,
                'tmp_name' => $tmpName,
                'type' => $type,
                'size' => $size
            ]
        );
    }
}
