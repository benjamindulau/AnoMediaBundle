<?php

namespace Ano\Bundle\MediaBundle\Model\Manager;

use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;
use Ano\Bundle\MediaBundle\Repository\MediaGroupRepositoryInterface;

class MediaGroupManager implements MediaGroupManagerInterface
{
    /* @var MediaGroupRepositoryInterface */
    protected $mediaGroupRepository;

    public function __construct(MediaGroupRepositoryInterface $mediaGroupRepository)
    {
        $this->mediaGroupRepository = $mediaGroupRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function saveOrUpdateMediaGroup(MediaGroupInterface $mediaGroup)
    {
        $this->mediaGroupRepository->save($mediaGroup);
    }
}