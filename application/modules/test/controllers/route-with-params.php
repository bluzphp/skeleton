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
 * @route /{$a}-{$b}-{$c}.html
 * @param int $a
 * @param float $b
 * @param string $c
 * @return \closure
 */
function($a, $b, $c) {
    return function() use ($a, $b, $c) {
        echo <<<CODE
<pre>
/**
 * @route /{\$a}-{\$b}-{\$c}.html
 * @param int \$a
 * @param float \$b
 * @param string \$c
 * @return closure
 */
</pre>
CODE;

        var_dump($a, $b, $c);
    };
};
