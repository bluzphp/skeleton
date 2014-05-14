<?php
/**
 * Delete image from redactor
 *
 * @category Application
 *
 * @author   dark
 * @created  19.06.13 18:18
 */
namespace Application;

return
/**
 * @var Bootstrap $this
 * @return void
 */
function ($path) {
    /**
     */
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
