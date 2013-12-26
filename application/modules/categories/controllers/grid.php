<?php
/**
 * Grid of Categories
 *
 * @author   Viacheslav Nogin
 * @created  25.11.12 18:39
 * @return closure
 */
namespace Application;

use Bluz;
use Application\Categories;
use Application\Categories\Table;

return
        /**
         *
         */
        function ($id = null) use ($view) {

            $view->headStyle($view->baseUrl('css/nestedSorteble.css'));

            $categoriesTable = Table::getInstance();

    try {

                $this->getLayout()->setTemplate('dashboard.phtml');
                $this->getLayout()->breadCrumbs(
                    [
                        $view->ahref('Dashboard', ['dashboard', 'grid']),
                        __('Categories')
                    ]
                );
                $allTrees = $categoriesTable->getAllRootCategory();

        if (count($allTrees) == 0) {
            throw new Exception('There are no categories');
        }


                $view->allTree = $allTrees;
        if (!$id) {
            $id = $allTrees[0]->id;
        }


        $view->branch = $id;
        $view->tree = $categoriesTable->buildTree($id);
    } catch (\Exception $e) {
        $view->error = $e->getMessage();
    }
        };
