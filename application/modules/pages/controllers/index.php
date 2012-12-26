<?php
/**
 * Example of static pages
 *
 * @author   Anton Shevchuk
 * @created  06.08.12 10:08
 */
namespace Application;

use Bluz;
use Application\Pages;

return
/**
 * @route /{$alias}.html
 * @param string $alias
 * @return \closure
 */
function($alias) use ($view) {

    /**
     * @var Pages\Row $page
     */
    $page = Pages\Table::getInstance()->getByAlias($alias);

    if (!$page) {
        throw new Exception('Page not found', 404);
    } else {
        $view->meta('keywords', $page->keywords);
        $view->meta('description', $page->description);
        $view->page = $page;
    }
};
