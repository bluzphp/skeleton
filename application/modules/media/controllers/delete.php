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
 * @return \closure
 */
function ($path) {
    /**
     * @var \Application\Bootstrap $this
     * @var Media\Table $mediaTable
     */
    $this->useJson();

    /**
     * @var Users\Row $user
     */
    $user = $this->getAuth()->getIdentity();

    $result = $this->getDb()
        ->delete('media')
        ->where('type LIKE (?)', 'image/%')
        ->andWhere('file = ?', $path)
        ->andWhere('userId = ?', $user->id)
        ->execute();

    return $result;
};
