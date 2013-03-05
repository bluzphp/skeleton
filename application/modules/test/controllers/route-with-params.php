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
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs([
        $this->getLayout()->ahref('Test', ['test', 'index']),
        'Routers Examples',
    ]);
    var_dump("OK");
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
    return 'route.phtml';
};
