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
use Application\Users\Row;
use Application\Users\Table;
use Bluz\Http\StatusCode;
use Bluz\Proxy\Auth;

/**
 * @group    users
 *
 * @package  Application\Tests\Users
 * @author   Anton Shevchuk
 * @created  17.07.17 11:42
 */
class ProfileTest extends ControllerTestCase
{
    public function testOpenProfileAsGuestShouldRedirectToLoginPage()
    {
        $this->dispatch('users/profile/id/2');

        self::assertModule('users');
        self::assertController('profile');
        self::assertResponseCode(StatusCode::FOUND);
    }

    public function testOpenForeignProfileAsMemberIsForbidden()
    {
        Auth::setIdentity(new Row());

        $this->dispatch('users/profile/id/2');

        self::assertModule('error');
        self::assertController('index');
        self::assertResponseCode(StatusCode::FORBIDDEN);
    }

    public function testOpenOwnProfileAsMember()
    {
        Auth::setIdentity(Table::findRow(2));

        $this->dispatch('users/profile');

        self::assertModule('users');
        self::assertController('profile');
        self::assertOk();
    }
}
