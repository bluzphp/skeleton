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
use Bluz\Controller\Controller;
use Bluz\Proxy\Config;
use Bluz\Proxy\Request;
use Bluz\Validator\Exception\ValidatorException;
use Zend\Diactoros\UploadedFile;

/**
 * @privilege Upload
 *
 * @return array
 * @throws ConfigException
 * @throws Exception
 */
return function () {
    /**
     * @var Controller $this
     * @var UploadedFile $file
     */
    $file = Request::getFile('file');

    $allowTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif'];

    // check media type
    if ($file && $file->getClientMediaType() && in_array($file->getClientMediaType(), $allowTypes)) {
        // save original name
        $original = pathinfo($file->getClientFilename(), PATHINFO_FILENAME);

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

        $file->moveTo($path.'/'.$userId.'/media/'.$filename);

        // save media data
        $media = new Media\Row();
        $media->userId = $userId;
        $media->module = 'media';
        $media->type = $file->getClientMediaType();
        $media->title = $original;
        $media->file = 'uploads/'.$userId.'/media/'.$filename;
        $media->size = filesize($path.'/'.$userId.'/media/'.$filename);

        try {
            $media->save();
        } catch (ValidatorException $e) {
            // remove invalid files
            $media->deleteFiles();

            // create error message
            $message = '';
            $errors = $e->getErrors();
            foreach ($errors as $field => $errs) {
                $message .= join("\n", $errs);
            }
            throw new Exception($message);
        }

        // displaying file
        return ['filelink' =>  $media->file];
    } else {
        throw new Exception('Wrong file type');
    }
};
