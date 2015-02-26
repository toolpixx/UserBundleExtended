<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 25.01.15
 * Time: 02:00
 */
namespace Common\Services;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class CropImage
 * @package Common
 */
class ImageService
{
    /**
     * @var
     */
    protected $imagePath;

    /**
     * @var
     */
    protected $imageCropY;

    /**
     * @var
     */
    protected $imageCropX;

    /**
     * @var
     */
    protected $imageCropHeight;

    /**
     * @var
     */
    protected $imageCropWidth;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get the path of the file that crop
     *
     * @return mixed
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set the path of the file that crop
     *
     * @param $imagePath
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * For cropping profile-picture
     *
     * @return mixed
     */
    public function getImageCropY()
    {
        return $this->imageCropY;
    }

    /**
     * For cropping profile-picture
     *
     * @param $imageCropY
     */
    public function setImageCropY($imageCropY)
    {
        $this->imageCropY = $imageCropY;
    }

    /**
     * For cropping profile-picture
     *
     * @return mixed
     */
    public function getImageCropX()
    {
        return $this->imageCropX;
    }

    /**
     * For cropping profile-picture
     *
     * @param $imageCropX
     */
    public function setImageCropX($imageCropX)
    {
        $this->imageCropX = $imageCropX;
    }

    /**
     * For cropping profile-picture
     *
     * @return mixed
     */
    public function getImageCropHeight()
    {
        return $this->imageCropHeight;
    }

    /**
     * For cropping profile-picture
     *
     * @param $imageCropHeight
     */
    public function setImageCropHeight($imageCropHeight)
    {
        $this->imageCropHeight = $imageCropHeight;
    }

    /**
     * For cropping profile-picture
     *
     * @return mixed
     */
    public function getImageCropWidth()
    {
        return $this->imageCropWidth;
    }

    /**
     * For cropping profile-picture
     *
     * @param $imageCropWidth
     */
    public function setImageCropWidth($imageCropWidth)
    {
        $this->imageCropWidth = $imageCropWidth;
    }

    /**
     * Crop profile-picture
     *
     * @param  $vars
     * @return bool
     */
    public function cropImage()
    {
        try {
            if (is_file($this->getImagePath())) {
                // Get the image-type
                $type = $this->getImageMimeType($this->getImagePath());

                // Get image source
                $source = $this->getImageSource($this->getImagePath(), $type);

                // Resample the picture
                $destination = imagecreatetruecolor(220, 220);

                imagecopyresampled(
                    $destination,
                    $source,
                    0,
                    0,
                    $this->imageCropX,
                    $this->imageCropY,
                    220,
                    220,
                    $this->imageCropWidth,
                    $this->imageCropHeight
                );

                  $this->getImageResult($this->getImagePath(), $type, $destination);

                imagedestroy($source);
                imagedestroy($destination);

                return true;
            }
        } catch (Exception $error) {
            throw new Exception($error->getMessage());
        }
        return false;
    }

    /**
     * @param $src
     * @return mimetype
     */
    private function getImageMimeType($src)
    {
        $imageInfo = getimagesize($src);
        return ($imageInfo['mime']) ? $imageInfo['mime'] : '';
    }

    /**
     * @param $path
     * @param $type
     * @return resource
     */
    private function getImageSource($path, $type) {

        // Read the picture
        switch ($type) {
            default:
                return '';
            case "image/gif":
                return imagecreatefromgif($path);
            case "image/jpeg":
                return imagecreatefromjpeg($path);
            case "image/png":
                return imagecreatefrompng($path);
        }
    }

    /**
     * @param $path
     * @param $type
     * @param $destination
     */
    private function getImageResult($path, $type, $destination) {

        $result = '';

        switch ($type) {
            case "image/gif":
                $result = imagegif($destination, $path);
                break;
            case "image/jpeg":
                $result = imagejpeg($destination, $path);
                break;
            case "image/png":
                $result = imagepng($destination, $path);
                break;
        }
        if (!$result) {
            throw new Exception('Failed to save the cropped image file');
        }
    }
}
