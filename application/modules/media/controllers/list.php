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
function() {
    /**
     * @var \Bluz\Application $this
     * @var Media\Table $mediaTable
     */
    $this->useJson();

    $user = $this->getAuth()->getIdentity();

    $mediaTable = Media\Table::getInstance();

    $images = $mediaTable->findWhere(['type LIKE' => 'image/%', 'userId' => $user->id]);

    $result = array();
    foreach ($images as $image) {
        $result[] = [
            "title" => $image->title,
            "image" => $image->file,
            "thumb" => $image->file,
            "folder" => $image->module,
        ];
    }
    return $result;
};