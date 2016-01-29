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
use Bluz\Proxy\Request;

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
     * @return integer
     */
    public function createOne($data)
    {
        /**
         * Process HTTP File
         */
        $file = Request::getFile('file');

        if (!$file or $file->getError() != UPLOAD_ERR_OK) {
            if ($file->getError() == UPLOAD_ERR_NO_FILE) {
                throw new Exception("Please choose file for upload");
            }
            throw new Exception("Sorry, I can't receive file");
        }

        /**
         * Generate image name
         */
        $pathinfo = pathinfo($file->getClientFilename());

        $fileName = strtolower(isset($data['title'])?$data['title']:$pathinfo['filename']);

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

        while (file_exists($this->uploadDir .'/'. $fileName .'.'. $pathinfo['extension'])) {
            $counter++;
            $fileName = $originFileName .'-'. $counter;
        }

        $filePath = $this->uploadDir .'/'. $fileName .'.'. $pathinfo['extension'];

        // Setup new name and move to user directory
        $file->moveTo($filePath);

        $publicDir = substr($this->uploadDir, strlen(PATH_PUBLIC) + 1);

        $data['file'] = $publicDir .'/'. $fileName .'.'. $pathinfo['extension'];
        $data['type'] = $file->getClientMediaType();

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
