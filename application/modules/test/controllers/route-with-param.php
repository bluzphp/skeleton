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
 * @route /test/param/{\$a}/
 * @param string \$a
 * @return closure
 */
</pre>
CODE;

    var_dump($a);
    return 'route.phtml';
};
