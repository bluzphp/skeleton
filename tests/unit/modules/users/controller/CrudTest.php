<?php

/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Users;

use Application\Tests\ControllerTestCase;

/**
 * @group    users
 * @group    crud
 *
 * @package  Application\Tests\Users
 * @author   Anton Shevchuk
 * @created  21.05.14 11:28
 */
class CrudTest extends ControllerTestCase
{
    /**
     * setUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->getApp()->useLayout(false);
        self::setupSuperUserIdentity();
    }

    /**
     * GET request should return FORM for create record
     */
    public function testCreateForm()
    {
        $this->dispatch('/users/crud/');
        self::assertOk();

        self::assertQueryCount('form[method="POST"]', 1);
    }

    /**
     * GET request with ID record should return FORM for edit
     */
    public function testEditForm()
    {
        $this->dispatch('/users/crud/', ['id' => 1]);
        self::assertOk();

        self::assertQueryCount('form[method="PUT"]', 1);
        self::assertQueryCount('input[name="id"][value="1"]', 1);
    }

    /**
     * GET request with wrong ID record should return ERROR 404
     */
    public function testEditFormError()
    {
        $this->dispatch('/users/crud/', ['id' => 100042]);
        self::assertResponseCode(404);
    }
}
