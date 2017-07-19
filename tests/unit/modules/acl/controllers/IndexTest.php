<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Acl;

use Application\Tests\ControllerTestCase;
use Application\Users\Row;
use Bluz\Proxy\Auth;

/**
 * @group    acl
 *
 * @package  Application\Tests\Acl
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class IndexTest extends ControllerTestCase
{
    public function testOpenAclIndexPageAsGuestShouldRedirectToLoginPage()
    {
        $this->dispatch('/acl/');
        self::assertRedirectToLogin();
    }

    public function testOpenAclIndexPageAsMemberIsForbidden()
    {
        Auth::setIdentity(new Row());

        $this->dispatch('/acl/');
        self::assertForbidden();
    }

    public function testOpenAclIndexPageAsAdministrator()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/acl/');
        self::assertOk();
    }
}
