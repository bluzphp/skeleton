<?php
/**
 * List of user images in JSON
 *
 * @category Application
 *
 * @author   dark
 * @created  12.02.13 14:18
 */
namespace Application;

return
/**
 * @return \closure
 */
function () {
    /**
     * @var \Bluz\Application $this
     * @var Media\Table $mediaTable
     */
    $this->useJson();

    /**
     * @var Users\Row $user
     */
    $user = $this->getAuth()->getIdentity();

    $images = $this->getDb()
        ->select('*')
        ->from('media', 'm')
        ->where('type LIKE (?)', 'image/%')
        ->andWhere('userId = ?', $user->id)
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
