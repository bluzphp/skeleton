<?php
namespace Application;

use Bluz\View\View;

return

    /**
     * @var View $view
     */
    function ($formName) use ($view) {
        $view->formName = $formName;
    };
