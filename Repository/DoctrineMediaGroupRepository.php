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
        $this->entityManager->persist($mediaGroup);
        $this->entityManager->flush();
    }
}