<?php
/**
 * Static pages
 *
 * @author   Anton Shevchuk
 * @created  06.08.12 10:08
 */
namespace Application;

use Bluz\Application\Exception\NotFoundException;
use Bluz\Proxy\Layout;
use Bluz\View\View;

return
/**
 * @route /{$alias}.html
 * @param string $alias
 * @return void
 */
function ($alias) use ($view) {
    /**
     * @var Bootstrap $this
     * @var View $view
     * @var Pages\Row $page
     */
    $page = Pages\Table::getInstance()->getByAlias($alias);

    if (!$page) {
        throw new NotFoundException();
    } else {
        Layout::title(esc($page->title), View::POS_PREPEND);
        Layout::meta('keywords', esc($page->keywords));
        Layout::meta('description', esc($page->description));
        $view->page = $page;
    }
};
