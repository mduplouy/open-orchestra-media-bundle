<?php

namespace OpenOrchestra\MediaBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use OpenOrchestra\DisplayBundle\Twig\NavigatorExtension;

/**
 * Class GalleryStrategy
 */
class GalleryStrategy extends AbstractStrategy
{
    const GALLERY = 'gallery';

    protected $request;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block)
    {
        return self::GALLERY == $block->getComponent();
    }

    /**
     * Perform the show action for a block
     *
     * @param BlockInterface $block
     *
     * @return Response
     */
    public function show(BlockInterface $block)
    {
        $parameters = $this->getParameters();

        $currentPage = $this->request->get(NavigatorExtension::PARAMETER_PAGE);
        if (!$currentPage) {
            $currentPage = 1;
        }

        return $this->render(
            'OpenOrchestraMediaBundle:Block/Gallery:show.html.twig',
            array(
                'galleryClass' => $block->getClass(),
                'galleryId' => $block->getId(),
                'pictures' => $this->filterMedias($block->getAttribute('pictures'), $currentPage, $block->getAttribute('itemNumber')),
                'numberOfColumns' => $block->getAttribute('columnNumber'),
                'thumbnailFormat' => $block->getAttribute('thumbnailFormat'),
                'imageFormat' => $block->getAttribute('imageFormat'),
                'numberOfPages' => ($block->getAttribute('itemNumber') == 0) ? 1 : ceil(count($block->getAttribute('pictures')) / $block->getAttribute('itemNumber')),
                'parameters' => $parameters,
                'currentPage' => $currentPage
            )
        );
    }

    /**
     * Generate an indexed array containing query parameters
     * formatted as (paramName => paramValue)
     * 
     * @return array
     */
    protected function getParameters()
    {
        $queryParams = $this->request->query->all();
        unset($queryParams['module_parameters']);

        return $queryParams;
    }

    /**
     * Filter medias to display
     * 
     * @param array $medias
     * @param int   $currentPage
     * @param int   $itemCount
     * 
     * @return array
     */
    protected function filterMedias($medias, $currentPage, $itemCount)
    {
        if (0 == $itemCount) {
            return $medias;
        }

        $filteredMedias = array();
        $offset = ($currentPage - 1)* $itemCount;
        for (
            $i = $offset;
            $i < $offset + $itemCount && isset($medias[$i]);
            $i++
        ) {
            $filteredMedias[] = $medias[$i];
        }
        return $filteredMedias;
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'gallery';
    }

}