<?php
/**
 * @author   Viacheslav Nogin
 * @created  25.11.12 18:39 18:39
 * @return   \Closure
 */

/**
 * @namespace
 */
namespace Application;

use Application\Categories;
use Bluz\Controller\Controller;
use Bluz\Proxy\Messages;

return
/**
 * @accept JSON
 * @privilege Management
 */
function ($tree, $treeParent) {
    /**
     * @var Controller $this
     */
    try {
        $categories = json_decode($tree);

        if (!$categories) {
            throw new Exception('Categories tree is broken');
        }

        foreach ($categories as $node) {
            if (isset($node->item_id)) {
                $dbNode = Categories\Table::findRow($node->item_id);

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
        Messages::addSuccess('Tree has been saved');
    } catch (\Exception $e) {
        Messages::addError($e->getMessage());
    }
};
