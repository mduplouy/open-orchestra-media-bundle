<?php

namespace OpenOrchestra\Media\Thumbnail\Strategies;

use OpenOrchestra\Media\Model\MediaInterface;
use OpenOrchestra\Media\Thumbnail\ThumbnailInterface;

/**
 * Class VideoToImageManager
 */
class VideoToImageManager implements ThumbnailInterface
{
    protected $tmpDir;
    protected $mediaDirectory;

    /**
     * @param string $tmpDir
     * @param string $mediaDirectory;
     */
    public function __construct($tmpDir, $mediaDirectory)
    {
        $this->tmpDir = $tmpDir;
        $this->mediaDirectory = $mediaDirectory;
    }

    /**
     * @param MediaInterface $media
     *
     * @return bool
     */
    public function support(MediaInterface $media)
    {
        return strpos($media->getMimeType(), 'video') === 0;
    }

    /**
     * @param MediaInterface $media
     *
     * @return MediaInterface
     */
    public function generateThumbnailName(MediaInterface $media)
    {
        $fileName = $media->getFilesystemName();
        $jpgFileName = substr($fileName, 0, -4). '.jpg';
        $media->setThumbnail($jpgFileName);

        return $media;
    }

    /**
     * @param MediaInterface $media
     *
     * @return MediaInterface
     */
    public function generateThumbnail(MediaInterface $media)
    {
        $video = new \ffmpeg_movie($this->tmpDir . '/' . $media->getFilesystemName());
        $frame = $video->getFrame(1);
        $image = $frame->toGDImage();
        imagejpeg($image, $this->mediaDirectory . '/' . $media->getThumbnail());

        return $media;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'video_to_image';
    }

}
