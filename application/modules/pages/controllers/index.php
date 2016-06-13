<?php
/**
 * Static pages
 *
 * @author   Anton Shevchuk
 * @created  06.08.12 10:08
 */
namespace Application;

use Bluz\Application\Exception\NotFoundException;
use Bluz\Controller\Controller;
use Bluz\Proxy\HttpCacheControl;
use Bluz\Proxy\Layout;
use Bluz\View\View;

/**
 * @route /{$alias}.html
 *
 * @param string $alias
 * @throws NotFoundException
 */
return function ($alias) {
    /**
     * @var Controller $this
     * @var Pages\Row $page
     */
    $page = Pages\Table::getInstance()->getByAlias($alias);

    if (!$page) {
        // throw NOT FOUND exception
        // all logic of error scenario you can found in default error controller
        // see /application/modules/error/controllers/index.php
        throw new NotFoundException();
    } else {
        // setup HTML layout data
        Layout::title(esc($page->title), View::POS_PREPEND);
        Layout::meta('keywords', esc($page->keywords));
        Layout::meta('description', esc($page->description));

        // setup HTTP cache
        HttpCacheControl::setPublic();
        HttpCacheControl::setLastModified($page->updated);

        // assign page to view
        $this->assign('page', $page);
    }
};
