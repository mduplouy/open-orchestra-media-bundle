<?php

namespace OpenOrchestra\Media\DisplayMedia\Strategies;

use OpenOrchestra\Media\DisplayMedia\DisplayMediaInterface;
use OpenOrchestra\Media\Model\MediaInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class AbstractStrategy
 */
abstract class AbstractStrategy implements DisplayMediaInterface
{
    protected $router;

    /**
     * Set the router
     * 
     * @param Router $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param MediaInterface $media
     *
     * @return String
     */
    public function displayPreview(MediaInterface $media)
    {
        return $this->getFileUrl($media->getThumbnail());
    }

    /**
     * Return url to a file stored with gaufrette
     * 
     * @param string $filename
     *
     * @return String
     */
    protected function getFileUrl($filename)
    {
        return $this->router->generate(
            'open_orchestra_media_get',
            array('key' => $filename),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
