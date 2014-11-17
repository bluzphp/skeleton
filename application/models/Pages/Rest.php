<?php
/**
 * @namespace
 */
namespace Application\Pages;

use Bluz\Application\Exception\ForbiddenException;
use Bluz\Controller;
use Bluz\Proxy\Request;

/**
 * Public Rest
 *
 * @package  Application\Pages
 *
 * @author   Anton Shevchuk
 * @created  13.11.2014 11:52
 */
class Rest extends Controller\Rest
{
    /**
     * Cut function of __invoke
     */
    public function __invoke()
    {
        // switch statement for $this->method
        switch ($this->method) {
            case Request::METHOD_POST:
            case Request::METHOD_PATCH:
            case Request::METHOD_PUT:
            case Request::METHOD_DELETE:
            case Request::METHOD_OPTIONS:
                throw new ForbiddenException();
            default:
                return parent::__invoke();
        }
    }
}
