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

use Bluz\Proxy\Db;

return
/**
 * @accept JSON
 * @var Bootstrap $this
 * @return int
 */
function ($path) {

    $this->useJson();

    /**
     * @var Users\Row $user
     */
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
