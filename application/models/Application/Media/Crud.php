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
use \Bluz\Crud\Table;

/**
 * Media Crud
 *
 * @category Application
 * @package  Media
 */
class Crud extends Table
{
    /**
     * @var string
     */
    protected $uploadDir;


    /**
     * @var \Bluz\Request\HttpFile
     */
    protected $file;

    /**
     * createOne
     *
     * @param array $data
     * @return integer
     */
    public function createOne($data)
    {
        $this->file = null;

        $this->validate(null, $data);
        $this->validateCreate($data);
        $this->checkErrors();

        $data['file'] = $this->uploadDir .'/'. $this->file->getFullName();
        $data['type'] = $this->file->getMimeType();

        $row = $this->getTable()->create();

        $row->setFromArray($data);
        return $row->save();
    }

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
     * {@inheritdoc}
     */
    public function validate($id, $data)
    {
        $this->checkTitle($data);
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreate($data)
    {
        /**
         * @var \Bluz\Request\HttpFile $file
         */
        try {
            $file = app()->getRequest()->getFileUpload()->getFile('file');
        } catch (\Bluz\Common\Exception $e) {
            $this->addError('file', $e->getMessage());
            return;
        }

        if (!$file) {
            $this->addError('file', "Sorry, I can't receive file");
            return;
        } elseif ($file->getErrorCode() != UPLOAD_ERR_OK) {
            switch ($file->getErrorCode()) {
                case UPLOAD_ERR_NO_FILE:
                    $this->addError('file', 'Please choose file for upload');
                    break;
            }
            return;
        }

        $title = isset($data['title'])?$data['title']:null;

        // prepare files for store
        $fileName = preg_replace('/([^a-z0-9])+/i', '-', strtolower($title));

        // process file
        $this->file = $file;
        $this->file->setName($fileName);
        $this->file->moveTo(PATH_PUBLIC .'/'. $this->uploadDir);
    }

    /**
     * {@inheritdoc}
     */
    public function validateUpdate($id, $data)
    {
        // check and replace original file and preview
    }

    /**
     * check title
     *
     * @param array $data
     * @return void
     */
    public function checkTitle($data)
    {
        // name validator
        $title = isset($data['title'])?$data['title']:null;

        if (empty($title)) {
            $this->addError('title', 'Title can\'t be empty');
        } elseif (!preg_match('/^[a-z0-9 .-]+$/i', $title)) {
            $this->addError('title', 'Title should contains only Latin characters');
        }
    }
}
