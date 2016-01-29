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

use Bluz\Config\ConfigException;
use Bluz\Http\File;
use Bluz\Proxy\Config;
use Bluz\Proxy\Request;
use Zend\Diactoros\UploadedFile;

return
/**
 * @privilege Upload
 * @return array
 */
function () {
    /**
     * @var Bootstrap $this
     * @var UploadedFile $file
     */
    $file = Request::getFile('file');

    if ($file && $file->getClientMediaType() == File::TYPE_IMAGE) {
        // save original name
        $original = $file->getClientFilename();

        // rename file to date/time stamp
        $filename = date('Ymd_Hi') .'.'. pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);

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
        $path = Config::getModuleData('media', 'upload_path');
        if (empty($path)) {
            throw new ConfigException('Upload path is not configured');
        }

        $file->moveTo($path.'/'.$userId.'/media');

        // save media data
        $media = new Media\Row();
        $media->userId = $userId;
        $media->module = 'media';
        $media->type = $file->getClientMediaType();
        $media->title = $original;
        $media->file = 'uploads/'.$userId.'/media/'.$filename;

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
