<?php

namespace OpenOrchestra\Media\Repository;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Interface MediaRepositoryInterface
 */
interface MediaRepositoryInterface
{
    /**
     * @param string $folderId
     *
     * @return Collection
     */
    public function findByFolderId($folderId);

    /**
     * @param string $keywords
     *
     * @return array
     */
    public function findByKeywords($keywords);

    /**
     * @param string $id
     *
     * @return MediaInterface
     */
    public function find($id);

    /**
     * @param string $name
     *
     * @return MediaInterface
     */
    public function findOneByName($name);
}
