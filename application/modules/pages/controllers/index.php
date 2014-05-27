<?php
/**
 * Static pages
 *
 * @author   Anton Shevchuk
 * @created  06.08.12 10:08
 */
namespace Application;

use Bluz\Application\Exception\NotFoundException;

return
/**
 * @route /{$alias}.html
 * @param string $alias
 * @return void
 */
function ($alias) use ($view) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     * @var Pages\Row $page
     */
    $page = Pages\Table::getInstance()->getByAlias($alias);

    if (!$page) {
        throw new NotFoundException();
    } else {
        $view->title(esc($page->title), \Bluz\View\View::POS_PREPEND);
        $view->meta('keywords', esc($page->keywords));
        $view->meta('description', esc($page->description));
        $view->page = $page;
    }
};
