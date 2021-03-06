<?php

namespace OpenOrchestra\Media\DisplayMedia;

use OpenOrchestra\Media\Model\MediaInterface;
use Symfony\Component\Routing\Router;

/**
 * Class DisplayMediaManager
 */
class DisplayMediaManager
{
    protected $strategies = array();
    protected $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param DisplayMediaInterface $strategy
     */
    public function addStrategy(DisplayMediaInterface $strategy)
    {
        $strategy->setRouter($this->router);
        $this->strategies[$strategy->getName()] = $strategy;
    }

    /**
     * @param MediaInterface $media
     *
     * @return String
     */
    public function displayPreview(MediaInterface $media)
    {
        /** @var DisplayMediaInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($media)) {
                return $strategy->displayPreview($media);
            }
        }

        return $media;
    }

    /**
     * @param MediaInterface $media
     *
     * @return String
     */
    public function displayMedia(MediaInterface $media)
    {
        /** @var DisplayMediaInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($media)) {
                return $strategy->displayMedia($media);
            }
        }

        return $media;
    }

    /**
     * @param MediaInterface $media
     * @param String         $format
     *
     * @return String
     */
    public function getMediaFormatUrl(MediaInterface $media, $format)
    {
        /** @var DisplayMediaInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($media)) {
                return $strategy->getMediaFormatUrl($media, $format);
            }
        }

        return $media;
    }
}
