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
 * @route /{$a}-{$b}-{$c}/
 * @param int $a
 * @param float $b
 * @param string $c
 * @return \closure
 */
function ($a, $b, $c) {
    /**
     * @var Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        [
            $this->getLayout()->ahref('Test', ['test', 'index']),
            'Routers Examples',
        ]
    );
    $uri = $this->getRequest()->getRequestUri();
    echo <<<CODE
<h4>URL: $uri</h4>
<h4>Route: {$this->getRequest()->getModule()}/{$this->getRequest()->getController()}</h4>
<pre>
/**
 * @route /{\$a}-{\$b}-{\$c}/
 * @param int \$a
 * @param float \$b
 * @param string \$c
 * @return closure
 */
</pre>
CODE;
    var_dump(['$a'=>$a, '$b'=>$b, '$c'=>$c]);
    var_dump($this->getRequest()->getParams());
    return false;
};
