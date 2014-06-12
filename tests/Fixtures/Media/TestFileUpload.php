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
     * Class that will be used to work with a downloadable file
     * @var string
     */
    protected $className = 'Application\Tests\Fixtures\Media\TestFile';

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
}
