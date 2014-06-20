<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Media;

use Application\Exception;

/**
 * Class Crud of Media
 * @package Application\Media
 *
 * @method Table getTable()
 */
class Crud extends \Bluz\Crud\Table
{
    /**
     * @var string
     */
    protected $uploadDir;

    /**
     * @var \Bluz\Http\File
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

        // Process image
        $file = app()->getRequest()->getFileUpload()->getFile('file');

        $fileName = strtolower(isset($data['title'])?$data['title']:$file->getName());

        // Generate image name
        $fileName = preg_replace('/[ _;:]+/i', '-', $fileName);
        $fileName = preg_replace('/[-]+/i', '-', $fileName);
        $fileName = preg_replace('/[^a-z0-9.-]+/i', '', $fileName);

        // If name is wrong
        if (empty($fileName)) {
            $fileName = date('Y-m-d-His');
        }

        // If file already exists, increment name
        $originFileName = $fileName;
        $counter = 0;

        while (file_exists($this->uploadDir .'/'. $fileName .'.'. $file->getExtension())) {
            $counter++;
            $fileName = $originFileName .'-'. $counter;
        }

        // Setup new name and move to user directory
        $file->setName($fileName);
        $file->moveTo($this->uploadDir);

        $this->uploadDir = substr($this->uploadDir, strlen(PATH_PUBLIC) + 1);
        $data['file'] = $this->uploadDir .'/'. $file->getFullName();
        $data['type'] = $file->getMimeType();

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
    public function validate($primary, $data)
    {
        $this->checkTitle($data);
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreate($data)
    {
        /**
         * @var \Bluz\Http\File $file
         */
        try {
            $file = app()->getRequest()->getFileUpload()->getFile('file');
        } catch (\Bluz\Common\Exception $e) {
            $this->addError($e->getMessage(), 'file');
            return;
        }

        if (!$file) {
            $this->addError("Sorry, I can't receive file", 'file');
            return;
        } elseif ($file->getErrorCode() != UPLOAD_ERR_OK) {
            switch ($file->getErrorCode()) {
                case UPLOAD_ERR_NO_FILE:
                    $this->addError('Please choose file for upload', 'file');
                    break;
            }
            return;
        }
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
            $this->addError('Title can\'t be empty', 'title');
        } elseif (!preg_match('/^[a-z0-9 .-]+$/i', $title)) {
            $this->addError('Title should contains only Latin characters', 'title');
        }
    }
}
