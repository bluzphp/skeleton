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

return
/**
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

    $result = $this->getDb()
        ->delete('media')
        ->where('type LIKE (?)', 'image/%')
        ->andWhere('file = ?', $path)
        ->andWhere('userId = ?', $userId)
        ->execute();

    return $result;
};
