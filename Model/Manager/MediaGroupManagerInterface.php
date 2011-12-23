<?php

namespace Ano\Bundle\MediaBundle\Model\Manager;

use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;

interface MediaGroupManagerInterface
{
    /**
     * Saves the media group
     *
     * @param MediaGroupInterface $mediaGroup
     */
    function saveOrUpdateMediaGroup(MediaGroupInterface $mediaGroup);
}