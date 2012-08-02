<?php
/**
 * Example of static route
 *
 * @author   Anton Shevchuk
 * @created  12.06.12 13:08
 */
namespace Application;
use Bluz;
return
/**
 * @route /{$alias}.html
 * @param string $alias
 * @return \closure
 */
function($alias) use ($view) {

    /**
     * @var \Application\Pages\Row $page
     */
    $page = \Application\Pages\Table::getInstance()->getByAlias($alias);

    if (!$page) {
        throw new \Exception('Page not found', 404);
    } else {
        $view->page = $page;
    }
};
