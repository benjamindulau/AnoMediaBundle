<?php

namespace Ano\Bundle\MediaBundle\Model\Manager;

use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;

interface MediaGroupManagerInterface
{
    /**
     * @param array $criteria
     *
     * @return MediaGroupInterface
     */
    function getMediaGroupBy(array $criteria);

    /**
     * Saves the media group
     *
     * @param MediaGroupInterface $mediaGroup
     */
    function saveOrUpdateMediaGroup(MediaGroupInterface $mediaGroup);
}