<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests;

use Bluz\Messages\Messages;
use Bluz\Router\Router;
use Application\Users;
use Application\Tests\Fixtures\Users\UserHasPermission;
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
    protected $document;

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->document = null;
    }

    /**
     * Setup Guest
     *
     * @return void
     */
    protected function setupGuestIdentity()
    {
        $this->getApp()->getAuth()->setIdentity(new Users\Row());
    }

    /**
     * Setup user with all privileges
     *
     * @return void
     */
    protected function setupSuperUserIdentity()
    {
        $this->getApp()->getAuth()->setIdentity(new UserHasPermission());
    }

    /**
     * Assert Response code is 200
     *
     * @return void
     */
    protected function assertOk()
    {
        $this->assertResponseCode(200);
    }

    /**
     * Assert Response code
     *
     * @param int $code
     * @return void
     */
    protected function assertResponseCode($code)
    {
        $this->assertEquals($code, $this->getApp()->getResponse()->getCode());
    }

    /**
     * Assert Module
     *
     * @param string $module
     * @return void
     */
    protected function assertModule($module)
    {
        $this->assertEquals($module, $this->getApp()->getModule());
    }

    /**
     * Assert Controller
     *
     * @param string $controller
     * @return void
     */
    protected function assertController($controller)
    {
        $this->assertEquals($controller, $this->getApp()->getController());
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
    protected function assertRedirect($module, $controller, $params = array(), $code = 302)
    {
        $url = $this->getApp()->getRouter()->url($module, $controller, $params);
        $exception = $this->getApp()->getResponse()->getException();

        $this->assertInstanceOf('\Bluz\Application\Exception\RedirectException', $exception);
        $this->assertEquals($exception->getCode(), $code);
        $this->assertEquals($exception->getMessage(), $url);
    }

    /**
     * Assert reload page
     *
     * @return void
     */
    protected function assertReload()
    {
        $exception = $this->getApp()->getResponse()->getException();

        $this->assertInstanceOf('\Bluz\Application\Exception\ReloadException', $exception);
    }

    /**
     * Assert forbidden
     *
     * @return void
     */
    protected function assertForbidden()
    {
        $exception = $this->getApp()->getResponse()->getException();

        $this->assertInstanceOf('\Bluz\Application\Exception\ForbiddenException', $exception);
        $this->assertEquals(403, $this->getApp()->getResponse()->getCode());
        $this->assertModule(Router::ERROR_MODULE);
        $this->assertController(Router::ERROR_CONTROLLER);
    }

    /**
     * Assert assigned variable
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function assertResponseVariable($key, $value)
    {
        if ($this->getApp()->hasLayout()) {
            $this->fail("Method `assertResponseVariable` required to disable Layout, please update test");
        }

        $variable = $this->getApp()->getResponse()->getBody()->__get($key);
        $this->assertEquals($variable, $value);
    }

    /**
     * Check Messages
     *
     * @param string $type
     * @param string $text
     * @return void
     */
    private function checkMessage($type, $text = null)
    {
        $message = $this->getApp()->getMessages()->pop($type);

        if (!$message) {
            $this->fail("System should be generated `$type` message");
        }

        if ($text) {
            $this->assertEquals($text, $message->text);
        }
    }

    /**
     * Assert Message with type Error
     *
     * @param string $text
     * @return void
     */
    protected function assertErrorMessage($text = null)
    {
        $this->checkMessage(Messages::TYPE_ERROR, $text);
    }

    /**
     * Assert Message with type Notice
     *
     * @param string $text
     * @return void
     */
    protected function assertNoticeMessage($text = null)
    {
        $this->checkMessage(Messages::TYPE_NOTICE, $text);
    }

    /**
     * Assert Message with type Success
     *
     * @param string $text
     * @return void
     */
    protected function assertSuccessMessage($text = null)
    {
        $this->checkMessage(Messages::TYPE_SUCCESS, $text);
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
    private function query($path)
    {
        $response = $this->getApp()->getResponse();

        if (!$this->document) {
            $this->document = new Document($response->getBody());
        }
        return Document\Query::execute($path, $this->document, Document\Query::TYPE_CSS);
    }

    /**
     * Count the DOM query executed
     *
     * @param  string $path
     * @return int
     */
    private function queryCount($path)
    {
        return count($this->query($path));
    }

    /**
     * Assert against DOM/XPath selection
     *
     * @param string $path
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function assertQuery($path)
    {
        $match = $this->queryCount($path);
        if (!$match > 0) {
            throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s EXISTS',
                    $path
                ));
        }
        $this->assertTrue($match > 0);
    }

    /**
     * Assert against DOM/XPath selection
     *
     * @param string $path CSS selector path
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function assertNotQuery($path)
    {
        $match  = $this->queryCount($path);
        if ($match != 0) {
            throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s DOES NOT EXIST',
                    $path
                ));
        }
        $this->assertEquals(0, $match);
    }

    /**
     * Assert against DOM/XPath selection; should contain exact number of nodes
     *
     * @param string $path CSS selector path
     * @param string $count Number of nodes that should match
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function assertQueryCount($path, $count)
    {
        $match = $this->queryCount($path);
        if ($match != $count) {
            throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s OCCURS EXACTLY %d times, actually occurs %d times',
                    $path,
                    $count,
                    $match
                ));
        }
        $this->assertEquals($match, $count);
    }

    /**
     * Assert against DOM/XPath selection; should NOT contain exact number of nodes
     *
     * @param  string $path CSS selector path
     * @param  string $count Number of nodes that should NOT match
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function assertNotQueryCount($path, $count)
    {
        $match = $this->queryCount($path);
        if ($match == $count) {
            throw new\ PHPUnit_Framework_ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s DOES NOT OCCUR EXACTLY %d times',
                    $path,
                    $count
                ));
        }
        $this->assertNotEquals($match, $count);
    }

    /**
     * Assert against DOM/XPath selection; node should contain content
     *
     * @param  string $path CSS selector path
     * @param  string $match content that should be contained in matched nodes
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function assertQueryContentContains($path, $match)
    {
        $result = $this->query($path);
        if ($result->count() == 0) {
            throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s EXISTS',
                    $path
                ));
        }
        foreach ($result as $node) {
            if ($node->nodeValue == $match) {
                $this->assertEquals($match, $node->nodeValue);
                return;
            }
        }
        throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
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
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function assertQueryContentRegex($path, $pattern)
    {
        $result = $this->query($path);

        if ($result->count() == 0) {
            throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                    'Failed asserting node DENOTED BY %s EXISTS',
                    $path
                ));
        }
        if (!preg_match($pattern, $result->current()->nodeValue)) {
            throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                    'Failed asserting node denoted by %s CONTAINS content MATCHING "%s", actual content is "%s"',
                    $path,
                    $pattern,
                    $result->current()->nodeValue
                ));
        }
        $this->assertTrue((bool) preg_match($pattern, $result->current()->nodeValue));
    }
}
