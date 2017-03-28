<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests;

use Bluz\Application\Exception\RedirectException;
use Bluz\Http\StatusCode;
use Bluz\Messages\Messages as MessagesInstance;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;
use Application\Users;
use Application\Tests\Fixtures\Users\UserHasPermission;
use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use Zend\Dom\Document;

/**
 * Controller TestCase
 *
 * @package Application\Tests
 *
 * @author   Anton Shevchuk
 * @created  06.05.2014 10:28
 */
class ControllerTestCase extends TestCase
{
    /**
     * @var Document
     */
    protected static $document;

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        self::$document = null;
    }

    /**
     * Setup Guest
     *
     * @return void
     */
    protected static function setupGuestIdentity()
    {
        Auth::setIdentity(new Users\Row());
    }

    /**
     * Setup user with all privileges
     *
     * @return void
     */
    protected static function setupSuperUserIdentity()
    {
        Auth::setIdentity(new UserHasPermission());
    }

    /**
     * Assert Response code is 200
     *
     * @return void
     */
    protected static function assertOk()
    {
        self::assertResponseCode(StatusCode::OK);
    }

    /**
     * Assert Response code
     *
     * @param int $code
     * @return void
     */
    protected static function assertResponseCode($code)
    {
        self::assertEquals($code, Response::getStatusCode());
    }

    /**
     * Assert Module
     *
     * @param string $module
     * @return void
     */
    protected static function assertModule($module)
    {
        self::assertEquals($module, self::getApp()->getModule());
    }

    /**
     * Assert Controller
     *
     * @param string $controller
     * @return void
     */
    protected static function assertController($controller)
    {
        self::assertEquals($controller, self::getApp()->getController());
    }

    /**
     * Assert redirect to another controller
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @param int $code
     * @return void
     */
    protected static function assertRedirect($module, $controller, $params = array(), $code = 302)
    {
        $url = Router::getUrl($module, $controller, $params);

        /**
         * @var RedirectException $exception
         */
        $exception = self::getApp()->getException();

        self::assertInstanceOf('\Bluz\Application\Exception\RedirectException', $exception);
        self::assertEquals($exception->getCode(), $code);
        self::assertEquals($exception->getUrl(), $url);
    }

    /**
     * Assert reload page
     *
     * @return void
     */
    protected static function assertReload()
    {
        $exception = self::getApp()->getException();

        self::assertInstanceOf('\Bluz\Application\Exception\ReloadException', $exception);
    }

    /**
     * Assert forbidden
     *
     * @return void
     */
    protected static function assertForbidden()
    {
        $exception = self::getApp()->getException();

        self::assertInstanceOf('\Bluz\Application\Exception\ForbiddenException', $exception);
        self::assertEquals(StatusCode::FORBIDDEN, Response::getStatusCode());
        self::assertModule(Router::getErrorModule());
        self::assertController(Router::getErrorController());
    }

    /**
     * Assert assigned variable
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected static function assertResponseVariable($key, $value)
    {
        if (self::getApp()->useLayout()) {
            self::fail("Method `assertResponseVariable` required to disable Layout, please update test");
        }

        $variable = Response::getBody()->getData()->__get($key);
        self::assertEquals($variable, $value);
    }

    /**
     * Check Messages
     *
     * @param string $type
     * @param string $text
     * @return void
     */
    private static function checkMessage($type, $text = null)
    {
        $message = Messages::pop($type);

        if (!$message) {
            self::fail("System should be generated `$type` message");
        }

        if ($text) {
            self::assertEquals($text, $message->text);
        }
    }

    /**
     * Assert Message with type Error
     *
     * @param string $text
     * @return void
     */
    protected static function assertErrorMessage($text = null)
    {
        self::checkMessage(MessagesInstance::TYPE_ERROR, $text);
    }

    /**
     * Assert Message with type Notice
     *
     * @param string $text
     * @return void
     */
    protected static function assertNoticeMessage($text = null)
    {
        self::checkMessage(MessagesInstance::TYPE_NOTICE, $text);
    }

    /**
     * Assert Message with type Success
     *
     * @param string $text
     * @return void
     */
    protected static function assertSuccessMessage($text = null)
    {
        self::checkMessage(MessagesInstance::TYPE_SUCCESS, $text);
    }

    /**
     * Asserts for HTML Dom elements
     * @see http://framework.zend.com/manual/2.2/en/modules/zend.dom.query.html
     * @see http://framework.zend.com/manual/2.2/en/modules/zend.test.phpunit.html#css-selector-assertions
     */

    /**
     * Execute a DOM query
     *
     * @param  string $path
     * @return Document\NodeList
     */
    private static function query($path)
    {
        if (!self::$document) {
            self::$document = new Document(Response::getBody());
        }
        return Document\Query::execute($path, self::$document, Document\Query::TYPE_CSS);
    }

    /**
     * Count the DOM query executed
     *
     * @param  string $path
     * @return int
     */
    private static function queryCount($path)
    {
        return count(self::query($path));
    }

    /**
     * Assert against DOM/XPath selection
     *
     * @param string $path
     * @throws PHPUnit_Framework_ExpectationFailedException
     */
    public static function assertQuery($path)
    {
        $match = self::queryCount($path);
        if (!$match > 0) {
            throw new ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s EXISTS',
                    $path
                ));
        }
        self::assertTrue($match > 0);
    }

    /**
     * Assert against DOM/XPath selection
     *
     * @param string $path CSS selector path
     * @throws ExpectationFailedException
     */
    public static function assertNotQuery($path)
    {
        $match  = self::queryCount($path);
        if ($match != 0) {
            throw new ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s DOES NOT EXIST',
                    $path
                ));
        }
        self::assertEquals(0, $match);
    }

    /**
     * Assert against DOM/XPath selection; should contain exact number of nodes
     *
     * @param string $path CSS selector path
     * @param string $count Number of nodes that should match
     * @throws ExpectationFailedException
     */
    public static function assertQueryCount($path, $count)
    {
        $match = self::queryCount($path);
        if ($match != $count) {
            throw new ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s OCCURS EXACTLY %d times, actually occurs %d times',
                    $path,
                    $count,
                    $match
                ));
        }
        self::assertEquals($match, $count);
    }

    /**
     * Assert against DOM/XPath selection; should NOT contain exact number of nodes
     *
     * @param  string $path CSS selector path
     * @param  string $count Number of nodes that should NOT match
     * @throws ExpectationFailedException
     */
    public static function assertNotQueryCount($path, $count)
    {
        $match = self::queryCount($path);
        if ($match == $count) {
            throw new ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s DOES NOT OCCUR EXACTLY %d times',
                    $path,
                    $count
                ));
        }
        self::assertNotEquals($match, $count);
    }

    /**
     * Assert against DOM/XPath selection; node should contain content
     *
     * @param  string $path CSS selector path
     * @param  string $match content that should be contained in matched nodes
     * @throws ExpectationFailedException
     */
    public static function assertQueryContentContains($path, $match)
    {
        $result = self::query($path);
        if ($result->count() == 0) {
            throw new ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s EXISTS',
                    $path
                ));
        }
        foreach ($result as $node) {
            if ($node->nodeValue == $match) {
                self::assertEquals($match, $node->nodeValue);
                return;
            }
        }
        throw new ExpectationFailedException(sprintf(
                'Failed asserting node denoted by %s CONTAINS content "%s"',
                $path,
                $match
            ));
    }

    /**
     * /**
     * Assert against DOM/XPath selection; node should match content
     *
     * @param  string $path CSS selector path
     * @param  string $pattern Pattern that should be contained in matched nodes
     * @throws ExpectationFailedException
     */
    public static function assertQueryContentRegex($path, $pattern)
    {
        $result = self::query($path);

        if ($result->count() == 0) {
            throw new ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s EXISTS',
                    $path
                ));
        }
        if (!preg_match($pattern, $result->current()->nodeValue)) {
            throw new ExpectationFailedException(sprintf(
                    'Failed asserting node denoted by %s CONTAINS content MATCHING "%s", actual content is "%s"',
                    $path,
                    $pattern,
                    $result->current()->nodeValue
                ));
        }
        self::assertTrue((bool) preg_match($pattern, $result->current()->nodeValue));
    }
}
