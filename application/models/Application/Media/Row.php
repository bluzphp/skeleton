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

use Application\Users;
use Application\Library\Image;

/**
 * Options Row
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
 * @category Application
 * @package  Options
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
        if ($user = app()->getAuth()->getIdentity()) {
            $this->userId = $user->id;
        } else {
            $this->userId = Users\Row::SYSTEM_USER;
        }

        // create preview
        $image = new Image($this->file);
        $image->setHeight(self::THUMB_HEIGHT);
        $image->setWidth(self::THUMB_WIDTH);

        $this->preview = $image->generateThumbnail();
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