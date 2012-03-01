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

    /**
     * {@inheritDoc}
     */
    public function saveOrUpdateMediaGroup(MediaGroupInterface $mediaGroup)
    {
        $this->fixMediaReferences($mediaGroup);
        $this->mediaGroupRepository->save($mediaGroup);
    }

    private function fixMediaReferences(MediaGroupInterface $mediaGroup)
    {
        foreach($mediaGroup->getMediaReferences() as $ref) {
            $ref->setMedia($this->mediaRepository->reloadMediaByUuid($ref->getMedia()));
            $ref->setGroup($mediaGroup);
        }
    }
}