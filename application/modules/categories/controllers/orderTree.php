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

                if ($dbNode->parentId != $node->parent_id && $node->parent_id) {
                    $dbNode->parentId = $node->parent_id;
                    $dbNode->save();
                }

                if ($dbNode->order != $node->order && $node->order) {
                    $dbNode->order = $node->order;
                    $dbNode->save();
                }
            }

        }
    } catch (\Exception $e) {
        $view->error = $e;
    }
    $this->useJSON();

        };
