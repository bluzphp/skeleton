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

return
/**
 * @var Bootstrap $this
 * @var Users\Row $user
 * @return array
 */
function () {
    $this->useJson();

    if (!$this->user()) {
        throw new Exception('User not found');
    }

    $userId = $this->user()->id;

    $images = $this->getDb()
        ->select('*')
        ->from('media', 'm')
        ->where('type LIKE (?)', 'image/%')
        ->andWhere('userId = ?', $userId)
        ->execute('Application\\Media\\Row');

    $result = array();
    foreach ($images as $image) {
        $result[] = [
            "title" => $image->title,
            "image" => $image->file,
            "thumb" => $image->preview,
            "folder" => $image->module,
        ];
    }
    return $result;
};
