<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Facebook;

use Application\Tests\ControllerTestCase;

/**
 * @package  Application\Tests\Facebook
 * @author   Anton Shevchuk
 * @created  17.06.2014 09:53
 */
class AuthTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testControllerPage()
    {
        /*
        $this->dispatchRouter('/facebook/auth/');
        */

        // Remove the following lines when you implement this test.
        // Need to refactoring Facebook library for avoid used global dependency
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * If user already signed - redirect to homepage
     */
    public function testControllerPageWithUser()
    {
        $this->setupSuperUserIdentity();

        $this->dispatchRouter('/facebook/auth/');
        $this->assertRedirect('index', 'index');
    }
}
