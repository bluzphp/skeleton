<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Media;

use Application\Exception;
use Application\Tests\ControllerTestCase;
use Application\Tests\Fixtures\Users\UserFixtureContainer;
use Application\Tests\Fixtures\Users\UserHasPermission;
use Zend\Dom\Document;
use Application\Tests\BootstrapTest;

/**
 * @package Application\Tests\Media
 * @author   Taras Seryogin
 */
class CrudTest extends ControllerTestCase
{
    /**
     * setUp
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->getApp()->getConfig('testing')->load('testing');
        $this->getApp()->getAuth()->setIdentity(new UserHasPermission(UserFixtureContainer::$fixture));
    }

    /**
     * TearDown
     *
     * Drop photo after the test
     *
     * @return void
     */
    protected function tearDown()
    {
        BootstrapTest::getInstance()->getDb()->delete('media')->where('userId', [1])->execute();
        $path = $this->getApp()->getConfigData('upload_dir', 'path').'/1';
        $this->delete($path);
    }

    /**
     * Delete photo from file system
     */
    public function delete($dirPath)
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
                $this->delete($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    /**
     * Test upload file
     */
    public function testUploadFile()
    {
        // get path from config
        $path = $this->getApp()->getConfigData('tmp_name', 'path');
        if (empty($path)) {
            throw new Exception('Temporary path is not configured');
        }

        $_FILES = array(
            'file' => array(
                'name' => 'test.jpg',
                'size' => filesize(PATH_ROOT.'/tests/Fixtures/Media/test.jpg'),
                'type' => 'image/jpeg',
                'tmp_name' => $path,
                'error' => 0
            )
        );

        $fileUpload = 'Application\Tests\Fixtures\Media\TestFileUpload';
        $request = $this->getApp()->getRequest();
        $request->setFileUpload(new $fileUpload());

        $this->dispatchUri(
            'media/crud',
            ['title' => 'test', 'file' => $_FILES['file']],
            'POST'
        );

        $this->assertAttributeValueEquals('input[name="title"]', 'test');
        $this->assertOk();
    }
}
