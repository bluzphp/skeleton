<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Layout\Helper;

return
/**
 * @param array $tree
 * @return string
 */
function ($tree) {
    /**
     * @var \Bluz\View\Layout $this
     */
    $str = '';
    foreach ($tree as $node) {

        if (empty($node['children'])) {
            $str .= '<li class="mjs-nestedSortable-leaf" data-order="'
                . $node['order'] . '" id="list_' . $node['id'] . '">'
                . $this->partial('category/navigation.phtml', ['node' => $node])
                . '</li>';
        } else {
            $str .= '<li class="mjs-nestedSortable-leaf" data-order="'
                . $node['order'] . '" id="list_' . $node['id'] . '">'
                . $this->partial('category/navigation.phtml', ['node' => $node])
                . '<ol>'
                . $this->treeBuild($node['children'])
                . '</ol>'
                . '</li>';
        }
    }

    return $str;
};
