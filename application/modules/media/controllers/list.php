<?php
/**
 * List of user images in JSON
 *
 * @author   Anton Shevchuk
 * @created  12.02.13 14:18
 */

/**
 * @namespace
 */
namespace Application;

use Application\Media\Table;
use Bluz\Controller\Controller;

/**
 * @accept JSON
 * @return array
 * @throws Exception
 */
return function () {
    /**
     * @var Controller $this
     */
    $images = Table::getInstance()->getImages();

    $result = array();
    foreach ($images as $image) {
        $result[] = [
            "id" => $image->id,
            "title" => $image->title,
            "image" => $image->file,
            "thumb" => $image->preview,
            "folder" => $image->module,
        ];
    }
    return $result;
};
