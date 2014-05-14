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
 * @property string $preview
 * @property string $created
 * @property string $updated
 */
class Row extends \Bluz\Db\Row
{
    const THUMB_HEIGHT = 120;
    const THUMB_WIDTH = 120;

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
        if ($user = app()->user()) {
            $this->userId = $user->id;
        } else {
            $this->userId = Users\Table::SYSTEM_USER;
        }

        // create preview
        // set full path
        $image = new Thumbnail(PATH_PUBLIC .'/'. $this->file);
        $image->setHeight(self::THUMB_HEIGHT);
        $image->setWidth(self::THUMB_WIDTH);
        $preview = $image->generate();
        // crop full path
        $preview = substr($preview, strlen(PATH_PUBLIC) + 1);
        $this->preview = $preview;
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
        if (is_file(PATH_PUBLIC .'/'. $this->file)) {
            @unlink(PATH_PUBLIC .'/'. $this->file);
        }
        if (is_file(PATH_PUBLIC .'/'. $this->preview)) {
            @unlink(PATH_PUBLIC .'/'. $this->preview);
        }
    }
}
