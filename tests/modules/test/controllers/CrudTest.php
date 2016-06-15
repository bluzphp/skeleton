<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Test;

use Application\Tests\ControllerTestCase;
use Bluz\Proxy\Db;
use Bluz\Proxy\Response;
use Bluz\Proxy\Request;

/**
 * @package  Application\Tests\Test
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
        Db::insert('test')->setArray(
            [
                'id' => 1,
                'name' => 'Donatello',
                'email' => 'donatello@turtles.org'
            ]
        )->execute();

        Db::insert('test')->setArray(
            [
                'id' => 2,
                'name' => 'Leonardo',
                'email' => 'leonardo@turtles.org'
            ]
        )->execute();

        Db::insert('test')->setArray(
            [
                'id' => 3,
                'name' => 'Michelangelo',
                'email' => 'michelangelo@turtles.org'
            ]
        )->execute();

        Db::insert('test')->setArray(
            [
                'id' => 4,
                'name' => 'Raphael',
                'email' => 'raphael@turtles.org'
            ]
        )->execute();
    }

    /**
     * Drop `test` table after the last test
     */
    public static function tearDownAfterClass()
    {
        Db::delete('test')->where('id IN (?)', [1,2,3,4])->execute();
        Db::delete('test')->where('email = ?', 'splinter@turtles.org')->execute();
    }

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->setupSuperUserIdentity();
        $this->getApp()->useLayout(false);
    }

    /**
     * GET request should return FORM for create record
     */
    public function testCreateForm()
    {
        $this->dispatch('/test/crud/');
        $this->assertOk();
        $this->assertQueryCount('form[method="POST"]', 1);
    }

    /**
     * GET request with ID record should return FORM for edit
     */
    public function testEditForm()
    {
        $this->dispatch('/test/crud/', ['id' => 1]);
        $this->assertOk();

        $this->assertQueryCount('form[method="PUT"]', 1);
        $this->assertQueryCount('input[name="id"][value="1"]', 1);
    }

    /**
     * GET request with wrong ID record should return ERROR 404
     */
    public function testEditFormError()
    {
        $this->dispatch('/test/crud/', ['id' => 100042]);
        $this->assertResponseCode(404);
    }

    /**
     * POST request should CREATE record
     */
    public function testCreate()
    {
        $this->dispatch(
            '/test/crud/',
            ['name' => 'Splinter', 'email' => 'splinter@turtles.org'],
            Request::METHOD_POST
        );
        $this->assertOk();

        $count = Db::fetchOne(
            'SELECT count(*) FROM `test` WHERE `name` = ?',
            ['Splinter']
        );
        $this->assertEquals($count, 1);
    }

    /**
     * POST request with empty data should return ERROR and information
     */
    public function testCreateValidationErrors()
    {
        $this->dispatch(
            '/test/crud/',
            ['name' => '', 'email' => ''],
            Request::METHOD_POST
        );

        $this->assertNotNull(Response::getBody()->getData()->get('errors'));
        $this->assertEquals(sizeof(Response::getBody()->getData()->get('errors')), 2);
        $this->assertOk();
    }

    /**
     * PUT request CRUD controller should UPDATE record
     */
    public function testUpdate()
    {
        $this->dispatch(
            '/test/crud/',
            ['id' => 2, 'name' => 'Leonardo', 'email' => 'leonardo@turtles.ua'],
            Request::METHOD_PUT
        );
        ;
        $this->assertOk();

        $id = Db::fetchOne(
            'SELECT `id` FROM `test` WHERE `email` = ?',
            ['leonardo@turtles.ua']
        );
        $this->assertEquals($id, 2);
    }

    /**
     * PUT request with invalid data should return ERROR and information
     */
    public function testUpdateValidationErrors()
    {
        $this->dispatch(
            '/test/crud/',
            ['id' => 2, 'name' => '123456', 'email' => 'leonardo[at]turtles.ua'],
            Request::METHOD_PUT
        );
        ;
        $this->assertNotNull(Response::getBody()->getData()->get('errors'));
        $this->assertEquals(sizeof(Response::getBody()->getData()->get('errors')), 2);
        $this->assertOk();
    }

    /**
     * DELETE request should remove record
     */
    public function testDelete()
    {
        $this->dispatch(
            '/test/crud/',
            ['id' => 3],
            Request::METHOD_DELETE
        );
        $this->assertOk();

        $count = Db::fetchOne(
            'SELECT count(*) FROM `test` WHERE `email` = ?',
            ['michelangelo@turtles.org']
        );
        $this->assertEquals($count, 0);
    }

    /**
     * DELETE request with invalid id should return ERROR
     */
    public function testDeleteError()
    {
        $this->dispatch(
            '/test/crud/',
            ['id' => 100042],
            Request::METHOD_DELETE
        );

        $this->assertResponseCode(404);
    }
}
