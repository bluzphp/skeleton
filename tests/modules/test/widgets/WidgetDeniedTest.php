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
        $this->getApp()->getAuth()->setIdentity(new UserHasPermission());
        $this->getApp()->widget('test', 'acl-denied');
    }

    /**
     * @expectedException \Bluz\Acl\AclException
     */
    public function testDenied()
    {
        $this->getApp()->widget('test', 'acl-denied');
    }
}
