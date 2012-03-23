<?php

namespace Ano\Bundle\MediaBundle\Model\Manager;

use Ano\Bundle\MediaBundle\Model\Factory;
use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;
use Ano\Bundle\MediaBundle\Repository\MediaGroupRepositoryInterface;

class MediaGroupManager implements MediaGroupManagerInterface
{
    protected $mediaGroupRepository;
    protected $factory;

    public function __construct(
        MediaGroupRepositoryInterface $mediaGroupRepository,
        Factory $factory
    )
    {
        $this->mediaGroupRepository = $mediaGroupRepository;
        $this->factory = $factory;
    }

    function getMediaGroupBy(array $criteria)
    {
        return $this->mediaGroupRepository->findOneBy($criteria);
    }

    public function saveOrUpdateMediaGroup(MediaGroupInterface $mediaGroup)
    {
        $this->mediaGroupRepository->save($mediaGroup);
    }
}