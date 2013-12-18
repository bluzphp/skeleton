<?php
/**
 * @author   Viacheslav Nogin
 * @created  25.11.12 18:39 18:39
 * @return closure
 */
namespace Application;

use Bluz;
use Application\Categories;

return
        /**
         *
         */
        function ($tree, $treeParent) use ($view) {

            $categoriesTable = Categories\Table::getInstance();

            try {
                $categoris = json_decode($tree);

                foreach ($categoris as $node) {
                    if (isset($node->item_id)) {
                        $dbNode = $categoriesTable->findRow($node->item_id);

                        if (!$node->parent_id) {
                            $node->parent_id = $treeParent;
                        }

                        if ($dbNode->parent_id != $node->parent_id && $node->parent_id) {
                            $dbNode->parent_id = $node->parent_id;
                            $dbNode->save();
                        }

                        if ($dbNode->ordering != $node->ordering && $node->ordering) {
                            $dbNode->ordering = $node->ordering;
                            $dbNode->save();
                        }
                    }

                }
            } catch (\Exception $e) {
                $view->error = $e;
            }


            $this->useJSON();
        };