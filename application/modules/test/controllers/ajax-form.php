<?php
namespace Application;

use Bluz\Controller\Data;

return

    /**
     * @var Data $data
     */
    function ($formName) use ($data) {
        $data->formName = $formName;
    };
