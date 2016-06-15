<?php
/**
 * Delete image from redactor
 *
 * @author   Anton Shevchuk
 * @created  19.06.13 18:18
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Proxy\Db;

/**
 * @accept JSON
 *
 * @param  $path
 * @return int
 * @throws Exception
 */
return function ($path) {
    /**
     * @var Controller $this
     * @var Users\Row $user
     */
    $this->useJson();

    if (!$this->user()) {
        throw new Exception('User not found');
    }

    $userId = $this->user()->id;

    $result = Db::delete('media')
        ->where('type LIKE (?)', 'image/%')
        ->andWhere('file = ?', $path)
        ->andWhere('userId = ?', $userId)
        ->execute();

    return $result;
};
