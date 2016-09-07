<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Media;

use Application\Users;
use Bluz\Proxy\Auth;
use Bluz\Validator\Traits\Validator;
use Bluz\Validator\Validator as v;
use Image\Thumbnail;

/**
 * Media Row
 *
 * @category Application
 * @package  Media
 *
 * @property integer $id
 * @property integer $userId
 * @property string $title
 * @property string $module
 * @property string $type
 * @property string $file
 * @property string $thumb
 * @property integer $size
 * @property string $created
 * @property string $updated
 */
class Row extends \Bluz\Db\Row
{
    use Validator;

    const THUMB_HEIGHT = 196;
    const THUMB_WIDTH = 196;

    /**
     * {@inheritdoc}
     */
    protected function beforeSave()
    {
        $this->addValidator(
            'title',
            v::required()->latinNumeric(' -_.')
        );
    }

    /**
     * Create thumbnail
     *
     * @return string
     */
    public function createThumbnail()
    {
        // set full path
        $image = new Thumbnail(PATH_PUBLIC .'/'. $this->file);
        $image->setHeight(self::THUMB_HEIGHT);
        $image->setWidth(self::THUMB_WIDTH);
        $thumb = $image->generate();
        // crop full path
        $thumb = substr($thumb, strlen(PATH_PUBLIC) + 1);
        $this->thumb = $thumb;

        return $thumb;
    }

    /**
     * Delete Files
     *
     * @return void
     */
    public function deleteFiles()
    {
        if ($this->file && is_file(PATH_PUBLIC .'/'. $this->file)) {
            @unlink(PATH_PUBLIC .'/'. $this->file);
        }
        if ($this->thumb && is_file(PATH_PUBLIC .'/'. $this->thumb)) {
            @unlink(PATH_PUBLIC .'/'. $this->thumb);
        }
    }

    /**
     * __insert
     *
     * @return void
     */
    protected function beforeInsert()
    {
        $this->created = gmdate('Y-m-d H:i:s');

        // set default module
        if (!$this->module) {
            $this->module = 'users';
        }
        // set user ID
        if ($user = Auth::getIdentity()) {
            $this->userId = $user->id;
        } else {
            $this->userId = Users\Table::SYSTEM_USER;
        }

        // create thumbnail
        $this->createThumbnail();
    }

    /**
     * __update
     *
     * @return void
     */
    protected function beforeUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }

    /**
     * postDelete
     *
     * @return void
     */
    protected function afterDelete()
    {
        $this->deleteFiles();
    }
}
