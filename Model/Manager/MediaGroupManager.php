<?php

namespace Ano\Bundle\MediaBundle\Model\Manager;

use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;
use Ano\Bundle\MediaBundle\Repository\MediaGroupRepositoryInterface;
use Ano\Bundle\MediaBundle\Repository\MediaRepositoryInterface;

class MediaGroupManager implements MediaGroupManagerInterface
{
    protected $mediaGroupRepository;
    protected $mediaRepository;

    public function __construct(MediaGroupRepositoryInterface $mediaGroupRepository,
                                MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaGroupRepository = $mediaGroupRepository;
        $this->mediaRepository = $mediaRepository;
    }

    public function saveOrUpdateMediaGroup(MediaGroupInterface $mediaGroup)
    {
        $this->mediaGroupRepository->save($mediaGroup);
    }
}