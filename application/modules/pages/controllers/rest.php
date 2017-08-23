<?php
/**
 * Public REST for pages
 *
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */

namespace Application;

use Application\Pages;
use Bluz\Controller\Controller;
use Bluz\Controller\Mapper\Rest;

/**
 * @accept JSON
 * @accept HTML
 *
 * @return mixed
 */
return function () {
    /**
     * @var Controller $this
     */
    $rest = new Rest(Pages\Crud::getInstance());

    $rest
        ->head('system', 'rest/head')
    ;
    $rest
        ->get('system', 'rest/get')
        ->fields([
            'id',
            'title',
            'content',
            'keywords',
            'description'
        ])
    ;

    return $rest->run();
};
