<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Application\Tests\Fixtures\Media;

use Bluz\Http\File;

/**
 * TestFile
 *
 * @package  Application\Tests
 *
 * @author   Taras Seryogin
 * @created  30.05.14 12:20
 */
class TestFile extends File
{
    /**
     * Construct of TestFile
     * @param array $data
     * @throws \Bluz\Request\RequestException
     */
    public function __construct($data)
    {
        parent::__construct($data);

    }

    /**
     * Move uploaded file to directory
     *
     * @param string $path
     * @return bool
     */
    public function moveTo($path)
    {
        $path = rtrim($path, '/');
        $path = $path . '/' . $this->getFullName();
        return copy($this->tmp, $path);
    }
}
