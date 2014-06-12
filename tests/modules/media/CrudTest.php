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
use Application\Tests\Fixtures\Users\User;
use Application\Tests\Fixtures\Users\UserHasPermission;
use Zend\Dom\Document;

/**
 * @package Application\Tests\Media
 * @author   Taras Seryogin
 */
class CrudTest extends ControllerTestCase
{
    /**
     * Photo id
     *
     * @var string
     */
    protected $photoId;
    /**
     * Title photo
     *
     * @var string
     */
    protected $title;

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->getApp()->getAuth()->setIdentity(new UserHasPermission(User::$fixture));
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
        $this->dispatchUri(
            'media/crud',
            ['id' => $this->photoId],
            'DELETE'
        );
    }

    /**
     * Test upload file
     */
    public function testUploadFile()
    {
        //get path from config
        $this->getApp()->getConfig('testing')->load('testing');
        $path = $this->getApp()->getConfigData('tmp_name', 'path');

        $_FILES = array(
            'file' => array(
                'name' => 'test.jpg',
                'size' => '595284',
                'type' => 'image/jpeg',
                'tmp_name' => $path,
                'error' => 0
            )
        );

        $className = 'Application\Tests\Fixtures\Media\TestFileUpload';
        $request = $this->getApp()->getRequest();
        $request->setUploadClassName($className);

        $this->dispatchUri(
            'media/crud',
            ['title' => 'test', 'file' => $_FILES['file']],
            'POST'
        );

        $node = $this->query('input[name="id"]');
        foreach ($node as $result) {
            $this->photoId = $result->getAttribute('value');
        }

        $node = $this->query('input[name="title"]');
        foreach ($node as $result) {
            $this->title = $result->getAttribute('value');
        }

        $this->assertEquals('test', $this->title);
        $this->assertOk();
    }
}
