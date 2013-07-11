<?php
/**
 * Upload media content to server
 *
 * @category Application
 *
 * @author   dark
 * @created  06.02.13 18:16
 */
namespace Application;

use Bluz\Request\HttpFile;
use Bluz\Request\HttpFileUpload;

return
/**
 * @privilege Upload
 * @return \closure
 */
function() {
    /**
     * @var \Bluz\Application $this
     * @var \Bluz\Request\HttpFileUpload $fileUpload
     */
    $fileUpload = $this->getRequest()->getFileUpload();
    $file = $fileUpload->getFile('file');

    if ($file && $file->getType() == HttpFile::TYPE_IMAGE) {
        // save original name
        $original = $file->getName();
        // rename file to date/time stamp
        $file->setName(date('Ymd_Hi'));

        // switch to JSON response
        $this->useJson();

        $userId = $this->getAuth()->getIdentity()->id;

        // directory structure:
        //   uploads/
        //     %userId%/
        //       %module%/
        //         filename.ext
        $file->moveTo(PATH_PUBLIC .'/uploads/'.$userId.'/media');

        // save media data
        $media = new Media\Row();
        $media->userId = $userId;
        $media->module = 'media';
        $media->type = $file->getMimeType();
        $media->title = $original;
        $media->file = 'uploads/'.$userId.'/media/'.$file->getFullName();

        $media->preview = $media->file;
        $media->save();

        // displaying file
        return array(
            'filelink' =>  $media->file
        );

    } else {
        throw new Exception('Wrong file type');
    }
};