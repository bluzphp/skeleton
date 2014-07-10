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
     * createOne
     *
     * @param array $data
     * @throws \Application\Exception
     * @throws \Bluz\Request\RequestException
     * @return integer
     */
    public function createOne($data)
    {
        /**
         * Process HTTP File
         * @var \Bluz\Http\File $file
         */
        $file = app()->getRequest()->getFileUpload()->getFile('file');

        if (!$file or $file->getErrorCode() != UPLOAD_ERR_OK) {
            if ($file->getErrorCode() == UPLOAD_ERR_NO_FILE) {
                throw new Exception("Please choose file for upload");
            }
            throw new Exception("Sorry, I can't receive file");
        }

        /**
         * Generate image name
         */
        $fileName = strtolower(isset($data['title'])?$data['title']:$file->getName());

        // Prepare filename
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
}
