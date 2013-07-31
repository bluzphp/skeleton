<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Application\Media;

use Application\Exception;
use \Bluz\Crud\ValidationException;
use \Bluz\Crud\CrudException;

/**
 * Media Crud
 *
 * @category Application
 * @package  Media
 */
class Crud extends \Bluz\Crud\Crud
{
    protected $uploadDir;

    /**
     * setUploadDir
     *
     * @param $directory
     * @throws Exception
     * @return self
     */
    public function setUploadDir($directory)
    {
        if (!is_dir($directory) && !@mkdir($directory, 0755, true)) {
            throw new Exception("Upload folder is not exists and I can't create it");
        }

        if (!is_writable($directory)) {
            throw new Exception("Upload folder is not writable");
        }

        $this->uploadDir = $directory;

        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function validate()
    {
        // name validator
        $title = $this->getData('title');
        if (empty($title)) {
            $this->addError('title', 'Title can\'t be empty');
        } elseif (!preg_match('/^[a-z0-9 .-]+$/i', $title)) {
            $this->addError('title', 'Title should contains only Latin characters');
        }
        // validate entity
        // ...
        if (sizeof($this->errors)) {
            throw new ValidationException('Validation error, please check errors stack');
        } else {

        }
    }

    /**
     * @throws ValidationException
     */
    public function validateCreate()
    {
        /**
         * @var \Bluz\Request\HttpFile $file
         */
        try {
            $file = $this->getApplication()->getRequest()->getFileUpload()->getFile('data[file]');
        } catch (\Bluz\Exception $e) {
            $this->addError('file', $e->getMessage());
            $this->validate();
        }

        if (!$file) {
            $this->addError('file', "Sorry, I can't receive file");
        } elseif ($file->getErrorCode() != UPLOAD_ERR_OK) {
            switch ($file->getErrorCode()) {
                case UPLOAD_ERR_NO_FILE:
                    $this->addError('file', 'Please choose file for upload');
                    break;
            }
        }

        $this->validate();

        // prepare files for store
        $fileName = preg_replace('/([^a-z0-9])+/i', '-', strtolower($this->data['title']));

        // process file
        $file->setName($fileName);
        $file->moveTo(PATH_PUBLIC .'/'. $this->uploadDir);

        $this->data['file'] = $this->uploadDir .'/'. $file->getFullName();
        $this->data['type'] = $file->getMimeType();

    }

    /**
     * @param $originalRow
     * @throws ValidationException
     */
    public function validateUpdate($originalRow)
    {
        $this->validate();

        // check and replace original file and preview
    }
}
