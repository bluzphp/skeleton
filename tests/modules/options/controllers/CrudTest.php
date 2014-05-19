<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Options;

use Application\Tests\ControllerTestCase;
use Bluz\Request\AbstractRequest;

/**
 * @package  Application\Tests\Options
 * @author   Anton Shevchuk
 * @created  15.05.14 12:35
 */
class CrudTest extends ControllerTestCase
{
    /**
     * @todo test functionality
     */
    public function testCrudPage()
    {
        $this->setupSuperUserIdentity();

        $this->dispatchRouter('/options/crud/');
        $this->assertOk();
    }

    /**
     * @todo test functionality
     */
    public function testCrudPost()
    {
        $this->setupSuperUserIdentity();

        $this->dispatchRouter('/options/crud/', null, AbstractRequest::METHOD_POST);
        $this->assertOk();
    }

    /**
     * @todo test functionality
     */
    public function testCrudPut()
    {
        $this->setupSuperUserIdentity();

        $this->dispatchRouter('/options/crud/', null, AbstractRequest::METHOD_PUT);
        $this->assertResponseCode(404);
    }

    /**
     * @todo test functionality
     */
    public function testCrudDelete()
    {
        $this->setupSuperUserIdentity();

        $this->dispatchRouter('/options/crud/', null, AbstractRequest::METHOD_DELETE);
        $this->assertResponseCode(404);
    }
}
