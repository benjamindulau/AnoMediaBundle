<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;

interface MediaGroupRepositoryInterface
{
    /**
     * @param MediaGroupInterface $mediaGroup
     *
     * @return void
     */
    public function save(MediaGroupInterface $mediaGroup);

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     *
     * @return MediaGroupInterface[]
     */
    public function findBy(array $criteria, $orderBy = array(), $limit = null, $offset = null);

    /**
     * @param array $criteria
     *
     * @return MediaGroupInterface
     */
    public function findOneBy(array $criteria);
}