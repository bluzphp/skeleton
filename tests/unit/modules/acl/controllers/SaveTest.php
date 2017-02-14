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

/**
 * @package  Application\Tests\Acl
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class SaveTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     *
     * @todo test functionality
     */
    public function testControllerPage()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/acl/save/');

        // privileges set is empty
        // then redirect to main acl page
        // and set error message
        self::assertRedirect('acl', 'index');
        self::assertErrorMessage();
    }
}
