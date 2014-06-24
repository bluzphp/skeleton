<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Media;

use Application\Tests\ControllerTestCase;
use Application\Tests\Utils;
use Bluz\Http;
use Application\Exception;
use Application\Tests\Fixtures\Media\TestFileUpload;
use Application\Tests\Fixtures\Users\UserFixtureContainer;
use Application\Tests\Fixtures\Users\UserHasPermission;
use Zend\Dom\Document;
use Application\Tests\BootstrapTest;

/**
 * @package  Application\Tests\Media
 * @author   Anton Shevchuk
 * @created  21.05.14 11:28
 */
class CrudTest extends ControllerTestCase
{
    /**
     * Setup `test` table before the first test
     */
    public static function setUpBeforeClass()
    {

    }

    /**
     * Drop photo after the test
     */
    public static function tearDownAfterClass()
    {
        BootstrapTest::getInstance()->getDb()->delete('media')->where('userId', [1])->execute();
        $path = app()->getConfigData('upload_dir', 'path').'/1';
        Utils\Cleaner::delete($path);
    }

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->getApp()->useLayout(false);
        $this->getApp()->getConfig('testing')->load('testing');
        $this->getApp()->getAuth()->setIdentity(new UserHasPermission(UserFixtureContainer::$fixture));
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

        $request = $this->getApp()->getRequest();
        $request->setFileUpload(new TestFileUpload());

        $this->dispatchUri(
            'media/crud',
            ['title' => 'test', 'file' => $_FILES['file']],
            'POST'
        );

        $this->assertQueryCount('input[name="title"]', 1);
        $this->assertOk();
    }

    /**
     * GET request should return FORM for create record
     */
    public function testCreateForm()
    {
        $this->dispatchRouter('/media/crud/');
        $this->assertOk();

        $this->assertQueryCount('form[method="POST"]', 1);
    }

    /**
     * GET request with ID record should return FORM for edit
     */
    public function testEditForm()
    {
        /*
        $this->dispatchRouter('/media/crud/', ['id' => 1]);
        $this->assertOk();

        $this->assertQueryCount('form[method="PUT"]', 1);
        $this->assertQueryCount('input[name="id"][value="1"]', 1);
        */

        // Remove the following lines when you implement this test.
        // Need to create element with ID
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * GET request with wrong ID record should return ERROR 404
     */
    public function testEditFormError()
    {
        $this->dispatchRouter('/media/crud/', ['id' => 100042]);
        $this->assertResponseCode(404);
    }
}
