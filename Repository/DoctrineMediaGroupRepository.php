<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;
use Doctrine\ORM\EntityManager;

class DoctrineMediaGroupRepository implements MediaGroupRepositoryInterface
{
    /* @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(MediaGroupInterface $mediaGroup)
    {
        $this->fixMediaReferences($mediaGroup);
        $this->entityManager->persist($mediaGroup);
        $this->entityManager->flush();
    }

    private function fixMediaReferences(MediaGroupInterface $mediaGroup)
    {
        $references = clone ($mediaGroup->getMediaReferences());

        foreach($references as $ref) {
            $newMedia = $this->entityManager->merge($ref->getMedia());
            $ref->setMedia($newMedia);

            if (null !== $ref->getId()) {
                $ref = $this->entityManager->merge($ref);
            }
        }

        $mediaGroup->setMediaReferences($references);
    }
}