<?php
/**
 *
 */
return
        function ($tree) {
            $str = '';

    foreach ($tree as $node) {

        if (empty($node['children'])) {
            $str .= '<li class="mjs-nestedSortable-leaf" data-order="'
                    . $node['order'] . '" id="list_' . $node['id'] . '">'
            . $this->partial('category/navigation.phtml', ['node' => $node]) .
            '</li>';
        } else {
            $str .= '<li class="mjs-nestedSortable-leaf" data-order="'
                    . $node['order'] . '" id="list_' . $node['id'] . '">'
            . $this->partial('category/navigation.phtml', ['node' => $node]);
            $str .= '<ol>';
            $str .= $this->treeBuild($node['children']);
            $str .= '</ol>';
            $str .= '</li>';
        }
    }

            return $str;
        };
