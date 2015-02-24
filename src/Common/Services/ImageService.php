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
    public function cropImage($vars)
    {
        $this->setImageCropY($vars['cropY']);
        $this->setImageCropX($vars['cropX']);
        $this->setImageCropHeight($vars['cropHeight']);
        $this->setImageCropWidth($vars['cropWidth']);
        $this->setImagePath($vars['cropImagePath']);

        try {

            if (is_file($this->getImagePath())) {

                // Get the image-type
                $type = $this->getImageMimeType($this->getImagePath());

                $source = $this->getImageSource($this->getImagePath(), $type);

                // Check if pictures can read
                if (!isset($source)) {
                    throw new Exception('Cannot read image.');
                }

                // Resample the picture
                $destination = imagecreatetruecolor(
                    220,
                    220
                );

                $result = imagecopyresampled(
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

                // If picture was resampled save
                if ($result) {
                    $this->getImageResult($this->getImagePath(), $type, $destination);
                } else {
                    throw new Exception('Failed to crop the image file');
                }

                // Make clean
                imagedestroy($source);
                imagedestroy($destination);

                return true;
            }
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * @param $src
     * @return null|mime-type
     */
    private function getImageMimeType($src)
    {
        $imageInfo = getimagesize($src);
        return ($imageInfo['mime']) ? $imageInfo['mime'] : '';
    }

    private function getImageSource($path, $type) {

        // Read the picture
        switch ($type) {
            case "image/gif":
                $source = imagecreatefromgif($path);
                break;
            case "image/jpeg":
                $source = imagecreatefromjpeg($path);
                break;
            case "image/png":
                $source = imagecreatefrompng($path);
                break;
        }
        return $source;
    }

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
        return $result;
    }
}
