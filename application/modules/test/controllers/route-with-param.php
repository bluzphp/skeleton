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
 * @route /test/param/{$a}/
 * @param string $a
 * @return \closure
 */
function($a) {
    return function() use ($a) {
        echo <<<CODE
<pre>
/**
 * @route /test/param/{\$a}/
 * @param string \$a
 * @return closure
 */
</pre>
CODE;

        var_dump($a);
    };
};
