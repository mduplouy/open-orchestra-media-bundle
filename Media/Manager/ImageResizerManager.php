<?php

namespace OpenOrchestra\Media\Manager;

use Imagick;
use OpenOrchestra\Media\Event\ImagickEvent;
use OpenOrchestra\Media\MediaEvents;
use OpenOrchestra\Media\Model\MediaInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ImageResizerManager
 */
class ImageResizerManager
{
    protected $compressionQuality;
    protected $dispatcher;
    protected $tmpDir;
    protected $formats;

    /**
     * @param string                   $tmpDir
     * @param array                    $formats
     * @param int                      $compressionQuality
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct($tmpDir, array $formats, $compressionQuality, $dispatcher)
    {
        $this->compressionQuality = $compressionQuality;
        $this->dispatcher = $dispatcher;
        $this->tmpDir = $tmpDir;
        $this->formats = $formats;
    }

    /**
     * @param MediaInterface $media
     */
    public function generateAllThumbnails(MediaInterface $media)
    {
        foreach ($this->formats as $key => $format) {
            $filePath = $this->tmpDir . '/' . $media->getFilesystemName();
            $this->resizeAndSaveImage($media, $key, $filePath);
        }
    }

    /**
     * @param MediaInterface $media
     * @param int            $x
     * @param int            $y
     * @param int            $h
     * @param int            $w
     * @param string         $format
     */
    public function crop(MediaInterface $media, $x, $y, $h, $w, $format)
    {
        $image = new Imagick($this->tmpDir . '/' . $media->getFilesystemName());
        $image->cropImage($w, $h, $x, $y);
        $this->resizeImage($this->formats[$format], $image);

        $this->saveImage($media, $image, $format);
    }

    /**
     * @param MediaInterface $media
     * @param string         $format
     */
    public function override(MediaInterface $media, $format)
    {
        $filename = $format . '-' . $media->getFilesystemName();
        $filePath = $this->tmpDir . '/' . $filename;
        $this->resizeAndSaveImage($media, $format, $filePath);
    }

    /**
     * @param MediaInterface $media
     * @param Imagick        $image
     * @param string         $key
     */
    protected function saveImage(MediaInterface $media, Imagick $image, $key)
    {
        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompressionQuality($this->compressionQuality);
        $image->stripImage();
        $filename = $key . '-' . $media->getFilesystemName();

        $event = new ImagickEvent($filename, $image);
        $this->dispatcher->dispatch(MediaEvents::RESIZE_IMAGE, $event);
    }

    /**
     * @param string  $format
     * @param Imagick $image
     */
    protected function resizeImage($format, Imagick $image)
    {
        if (array_key_exists('width', $format) && array_key_exists('height', $format)) {
            $this->resizeInBox($image, $format['width'], $format['height']);

        } elseif (array_key_exists('max_height', $format)) {
            $this->resizeOnHeight($image, $format['max_height']);

        } elseif (array_key_exists('max_width', $format)) {
            $this->resizeOnWidth($image, $format['max_width']);
        }
    }

    /**
     * Resize an image keeping its ratio to the width $width
     * 
     * @param Imagick $image
     * @param int $width
     */
    protected function resizeOnWidth(Imagick $image, $width)
    {
        $image->resizeImage($width, 0, Imagick::FILTER_LANCZOS, 1);
    }

    /**
     * Resize an image keeping its ratio to the height $height
     * 
     * @param Imagick $image
     * @param int $height
     */
    protected function resizeOnHeight(Imagick $image, $height)
    {
        $image->resizeImage(0, $height, Imagick::FILTER_LANCZOS, 1);
    }

    /**
     * Resize an image keeping its ratio to make it standing in a box of width $width and height $height
     * 
     * @param Imagick $image
     * @param int $width
     * @param int $height
     */
    protected function resizeInBox(Imagick $image, $width, $height)
    {
        $image->setimagebackgroundcolor('#000000');
        $refRatio = $width / $height;
        $imageRatio = $image->getImageWidth() / $image->getImageHeight();

        if ($refRatio > $imageRatio) {
            $this->resizeOnHeight($image, $height);
        } else {
            $this->resizeOnWidth($image, $width);
        }
    }

    /**
     * @param MediaInterface $media
     * @param $format
     * @param $filePath
     */
    protected function resizeAndSaveImage(MediaInterface $media, $format, $filePath)
    {
        $image = new Imagick($filePath);
        $this->resizeImage($this->formats[$format], $image);

        $this->saveImage($media, $image, $format);
    }
}
