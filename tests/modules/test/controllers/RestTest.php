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
class RestTest extends ControllerTestCase
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
        Response::setHeader('Content-Type', 'application/json');
    }

    /**
     * GET request with PRIMARY should return one record
     */
    public function testReadOne()
    {
        $this->dispatch('/test/rest/1');

        $this->assertOk();

        /** @var \Application\Test\Row $row */
        $row = current(Response::getBody()->getData()->toArray());


        $this->assertEquals($row->id, 1);
    }

    /**
     * GET request should return SET of records
     */
    public function testReadSet()
    {
        $this->dispatch('/test/rest/', ['offset' => 0, 'limit' => 3]);

        $this->assertResponseCode(206);
        $this->assertEquals(sizeof(Response::getBody()->getData()->toArray()), 3);
        $this->assertEquals(Response::getHeader('Content-Range'), 'items 0-3/45');
    }

    /**
     * POST request with params should CREATE row and return PRIMARY
     */
    public function testCreate()
    {
        $this->dispatch(
            '/test/rest/',
            ['name' => 'Splinter', 'email' => 'splinter@turtles.org'],
            Request::METHOD_POST
        );

        $primary = Db::fetchOne(
            'SELECT id FROM `test` WHERE `name` = ?',
            ['Splinter']
        );

        $this->assertResponseCode(201);
        $this->assertEquals(Response::getHeader('Location'), '/test/rest/'.$primary);
    }

    /**
     * POST request with PRIMARY should return ERROR
     */
    public function testCreateWithPrimaryError()
    {
        $this->dispatch('/test/rest/1', [], Request::METHOD_POST);
        $this->assertResponseCode(501);
    }

    /**
     * POST request without DATA should return ERROR
     */
    public function testCreateWithoutDataError()
    {
        $this->dispatch('/test/rest/', [], Request::METHOD_POST);
        $this->assertResponseCode(400);
    }

    /**
     * POST request with invalid data should return ERROR and information
     */
    public function testCreateValidationErrors()
    {
        $this->dispatch(
            '/test/rest/',
            ['name' => '', 'email' => ''],
            Request::METHOD_POST
        );

        $this->assertNotNull(Response::getBody()->getData()->get('errors'));
        $this->assertEquals(sizeof(Response::getBody()->getData()->get('errors')), 2);
        $this->assertResponseCode(400);
    }

    /**
     * PUT request with PRIMARY should UPDATE record
     */
    public function testUpdate()
    {
        $this->dispatch(
            '/test/rest/2',
            ['name' => 'Leonardo', 'email' => 'leonardo@turtles.ua'],
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
     * PUT request with SET of DATA should UPDATE SET
     * @todo update set is not implemented in Crud\Table package
     */
    public function testUpdateSet()
    {
        $this->dispatch(
            '/test/rest/',
            [
                ['id' => 3, 'name' => 'Michelangelo', 'email' => 'michelangelo@turtles.org.ua'],
                ['id' => 4, 'name' => 'Raphael', 'email' => 'Raphael@turtles.org.ua'],
            ],
            Request::METHOD_PUT
        );

        $this->assertResponseCode(501);

        /*
        $this->assertOk();

        $count = Db::fetchOne(
            'SELECT count(*) FROM `test` WHERE `email` LIKE(?)',
            ['%turtles.org.ua']
        );
        $this->assertEquals($count, 2);
        */
    }

    /**
     * PUT request with same PRIMARY should return code 304
     */
    public function testUpdateWithSameData()
    {
        $this->dispatch('/test/rest/1', ['name' => 'Donatello'], Request::METHOD_PUT);
        $this->assertResponseCode(304);
    }

    /**
     * PUT request with invalid PRIMARY should return ERROR
     */
    public function testUpdateWithInvalidPrimary()
    {
        $this->dispatch('/test/rest/100042', ['name' => 'Raphael'], Request::METHOD_PUT);
        $this->assertResponseCode(404);
    }

    /**
     * PUT request without DATA should return ERROR
     */
    public function testUpdateWithoutDataError()
    {
        $this->dispatch('/test/rest/', [], Request::METHOD_PUT);
        $this->assertResponseCode(400);
    }

    /**
     * DELETE request with PRIMARY
     */
    public function testDelete()
    {
        $this->dispatch('/test/rest/1', [], Request::METHOD_DELETE);
        $this->assertResponseCode(204);

        $count = Db::fetchOne(
            'SELECT count(*) FROM `test` WHERE `id` = ?',
            [1]
        );
        $this->assertEquals($count, 0);
    }

    /**
     * DELETE request with invalid PRIMARY should return ERROR
     */
    public function testDeleteWithInvalidPrimary()
    {
        $this->dispatch('/test/rest/100042', [], Request::METHOD_DELETE);
        $this->assertResponseCode(404);
    }

    /**
     * DELETE request with SET of DATA should DELETE SET
     * @todo update set is not implemented in Crud\Table package
     */
    public function testDeleteSet()
    {
        $this->dispatch(
            '/test/rest/',
            [
                ['id' => 3],
                ['id' => 4],
            ],
            Request::METHOD_DELETE
        );

        $this->assertResponseCode(501);

        /*
        $this->assertOk();

        $count = Db::fetchOne(
            'SELECT count(*) FROM `test` WHERE `id` IN (3,4)'
        );
        $this->assertEquals($count, 0);
        */
    }
}
