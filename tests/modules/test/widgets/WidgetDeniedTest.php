<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Test;

use Application\Tests\TestCase;
use Application\Tests\Fixtures\Users\UserHasPermission;
use Bluz\Proxy\Auth;

/**
 * @package Application\Tests\Test
 * @author   Anton Shevchuk
 * @created  19.05.2014 12:08
 */
class WidgetDeniedTest extends TestCase
{
    /**
     * Try with permissions
     */
    public function testAllow()
    {
        Auth::setIdentity(new UserHasPermission());
        $this->getApp()->widget('test', 'acl-denied');
    }

    /**
     * @expectedException \Bluz\Application\Exception\ForbiddenException
     */
    public function testDenied()
    {
        $this->getApp()->widget('test', 'acl-denied');
    }
}
