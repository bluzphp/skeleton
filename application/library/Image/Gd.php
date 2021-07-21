<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */

namespace Image;

/**
 * Wrapper over Gd for support some Imagick functions
 *
 * @category Application
 * @package  Library
 */
class Gd
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var resource
     */
    protected $image;

    /**
     * @var integer
     */
    protected $width;

    /**
     * @var integer
     */
    protected $height;

    /**
     * @var integer
     */
    protected $type;

    /**
     * Compression quality for JPEG
     *
     * @var integer
     */
    protected $quality = 86;

    /**
     * Constructor of Gd
     *
     * @param string $file
     *
     * @throws Exception
     */
    public function __construct(string $file)
    {
        if (!file_exists($file)) {
            throw new Exception("Image `$file` file not found");
        }
        $this->file = $file;

        // Retrieve image information
        [$this->width, $this->height, $this->type] = getimagesize($file);

        // Check support of file type
        if (!(imagetypes() & $this->type)) {
            throw new Exception('Server does not support this image type');
        }

        // Using imagecreatefromstring will automatically detect the file type
        $this->image = imagecreatefromstring(file_get_contents($file));

        if (!$this->image) {
            throw new Exception('Could not load image');
        }
    }

    /**
     * @param integer $quality
     *
     * @return void
     */
    public function setImageCompressionQuality(int $quality): void
    {
        $this->quality = $quality;
    }

    /**
     * Create a crop thumbnail image from source image
     * cropped by smaller side
     *
     * @param integer $width
     * @param integer $height
     *
     * @return bool
     */
    public function cropThumbnailImage(int $width, int $height): bool
    {
        // Compare image size with required thumbnail size
        if (
            ($this->width < $width) &&
            ($this->height < $height)
        ) {
            return true;
        }

        $widthScale = round($this->width / $width);
        $heightScale = round($this->height / $height);

        if ($heightScale < $widthScale) {
            // Crop width
            $cropWidth = $heightScale * $width;
            $cropHeight = $this->height;
            $srcX = round(($this->width - $cropWidth) / 2);
            $srcY = 0;
        } else {
            // Crop height
            $cropWidth = $this->width;
            $cropHeight = $widthScale * $height;
            $srcX = 0;
            $srcY = round(($this->height - $cropHeight) / 2);
        }

        $thumb = imagecreatetruecolor($width, $height);

        // Copy resampled makes a smooth thumbnail
        imagecopyresampled($thumb, $this->image, 0, 0, $srcX, $srcY, $width, $height, $cropWidth, $cropHeight);
        imagedestroy($this->image);

        $this->width = $width;
        $this->height = $height;
        $this->image = $thumb;

        return (bool)$thumb;
    }

    /**
     * Save the image to a file. Type is determined from the extension
     *
     * @param string $fileName
     *
     * @return bool
     */
    public function writeImage(string $fileName): bool
    {
        if (!$this->image || file_exists($fileName)) {
            return false;
        }

        $ext = strtolower(substr($fileName, strrpos($fileName, '.')));

        switch ($ext) {
            case '.gif':
                return imagegif($this->image, $fileName);
            // break
            case '.jpg':
            case '.jpeg':
                return imagejpeg($this->image, $fileName, $this->quality);
            // break
            case '.png':
                return imagepng($this->image, $fileName);
            // break
            case '.bmp':
                return imagewbmp($this->image, $fileName);
            // break
            default:
                return false;
        }
    }

    /**
     * Destroy image source
     */
    public function __destruct()
    {
        imagedestroy($this->image);
    }
}
