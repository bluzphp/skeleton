<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Categories;

use Application\Tests\ControllerTestCase;
use Bluz\Http;

/**
 * @package  Application\Tests\Categories
 * @author   Anton Shevchuk
 * @created  17.06.14 10:39
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
     * Drop `test` table after the last test
     */
    public static function tearDownAfterClass()
    {

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
        $this->setupSuperUserIdentity();
    }

    /**
     * GET request should return FORM for create record
     */
    public function testCreateForm()
    {
        $this->dispatchRouter('/categories/crud/');
        $this->assertOk();

        $this->assertQueryCount('form[method="POST"]', 1);
    }

    /**
     * GET request with ID record should return FORM for edit
     */
    public function testEditForm()
    {
        /*
        $this->dispatchRouter('/categories/crud/', ['id' => 1]);
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
        $this->dispatchRouter('/categories/crud/', ['id' => 100042]);
        $this->assertResponseCode(404);
    }
}
