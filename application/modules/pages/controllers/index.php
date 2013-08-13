<?php
/**
 * Static pages
 *
 * @author   Anton Shevchuk
 * @created  06.08.12 10:08
 */
namespace Application;

return
/**
 * @route /{$alias}.html
 * @param string $alias
 * @return \closure
 */
function ($alias) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     * @var Pages\Row $page
     */
    $page = Pages\Table::getInstance()->getByAlias($alias);

    if (!$page) {
        throw new Exception('Page not found', 404);
    } else {
        $view->title(esc($page->title), \Bluz\View\View::POS_PREPEND);
        $view->meta('keywords', esc($page->keywords));
        $view->meta('description', esc($page->description));
        $view->page = $page;
    }
};
