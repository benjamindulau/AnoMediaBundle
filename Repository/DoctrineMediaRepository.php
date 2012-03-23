<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\Media;
use Ano\Bundle\MediaBundle\Model\Factory;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\UnitOfWork;

class DoctrineMediaRepository implements MediaRepositoryInterface
{
    protected $entityManager;
    protected $factory;

    public function __construct(EntityManager $entityManager, Factory $factory)
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    public function save(Media $media)
    {
        $this->entityManager->persist($media);
        $this->entityManager->flush();
    }

    public function delete(Media $media)
    {
        $this->entityManager->remove($media);
        $this->entityManager->flush();
    }
}