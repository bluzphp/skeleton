<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Image;

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
     * @param string $file
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
     * @throws Exception
     * @return string Path to new file
     */
    public function generate()
    {
        $dir = $this->path .'/.thumb/'.$this->width.'x'.$this->height;

        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new Exception("Thumbnail image can't be save. Parent directory is not writable");
        }

        // Thumbnail already exists
        // then remove it and regenerate
        if (file_exists($dir.'/'.$this->file)) {
            unlink($dir.'/'.$this->file);
        }

        if (class_exists('\\Imagick')) {
            $image = new \Imagick($this->path.'/'.$this->file);
        } elseif (function_exists('gd_info')) {
            $image = new Gd($this->path.'/'.$this->file);
        } else {
            // return original file
            return $this->path .'/'. $this->file;
        }

        $image->cropThumbnailImage($this->width, $this->height);
        $image->writeimage($dir.'/'.$this->file);

        return $dir .'/'. $this->file;
    }
}
