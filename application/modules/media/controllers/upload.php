<?php
/**
 * Upload media content to server
 *
 * @author   Anton Shevchuk
 * @created  06.02.13 18:16
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Http\File;

return
/**
 * @privilege Upload
 * @return array
 */
function () {
    /**
     * @var Bootstrap $this
     * @var \Bluz\Http\FileUpload $fileUpload
     */
    $fileUpload = $this->getRequest()->getFileUpload();
    $file = $fileUpload->getFile('file');

    if ($file && $file->getType() == File::TYPE_IMAGE) {
        // save original name
        $original = $file->getName();
        // rename file to date/time stamp
        $file->setName(date('Ymd_Hi'));

        // switch to JSON response
        $this->useJson();

        if (!$this->user()) {
            throw new Exception('User not found');
        }

        $userId = $this->user()->id;

        // directory structure:
        //   uploads/
        //     %userId%/
        //       %module%/
        //         filename.ext
        $path = $this->getConfigData('upload_dir', 'path');
        if (empty($path)) {
            throw new Exception('Upload path is not configured');
        }
        $file->moveTo($path.'/'.$userId.'/media');

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
