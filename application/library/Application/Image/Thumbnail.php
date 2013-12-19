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
namespace Application\Image;

use Application\Exception;

/**
 * Image resize and crop
 *
 * @category Application
 * @package  Library
 *
 * @author   Anton Shevchuk
 * @created  21.06.13 11:29
 */
class Thumbnail
{
    /**
     * Full path to file
     *
     * @var string
     */
    protected $path;

    /**
     * Filename
     *
     * @var string
     */
    protected $file;

    /**
     * Width of thumbnail, zero means leave original size
     * @var int
     */
    protected $width = 0;

    /**
     * Height of thumbnail, zero means leave original size
     * @var int
     */
    protected $height = 0;

    /**
     * Constructor of Image Tool
     *
     * @access  public
     */
    public function __construct($file)
    {
        $this->path = dirname($file);
        $this->file = substr($file, strlen($this->path)+1);
    }

    /**
     * Setup width
     *
     * @param int $width
     * @return self
     */
    public function setWidth($width)
    {
        $this->width = (int)$width;
        return $this;
    }

    /**
     * Setup height
     *
     * @param int $height
     * @return self
     */
    public function setHeight($height)
    {
        $this->height = (int)$height;
        return $this;
    }

    /**
     * Generate thumbnail
     *
     * @throws \Application\Exception
     * @return string Path to new file
     */
    public function generate()
    {
        $dir = $this->path .'/.thumb/'.$this->width.'x'.$this->height;

        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new Exception("Thumbnail image can't be save. Parent directory is not writable");
        }

        if (class_exists('\\Imagick')) {
            $image = new \Imagick($this->path.'/'.$this->file);
            $image->cropThumbnailImage($this->width, $this->height);
            $image->writeimage($dir.'/'.$this->file);
        } elseif (function_exists('gd_info')) {
            // TODO: Implementation image manipulation with gd_info
        } else {
            // return original file
            return $this->path .'/'. $this->file;
        }

        return $dir .'/'. $this->file;
    }
}
