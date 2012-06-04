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

    public function find($id)
    {
        $class = $this->factory->getClass('media');

        return $this->entityManager->getRepository($class)->find($id);
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

    public function findMediaReference($id)
    {
        return $this->entityManager->getRepository($this->factory->getClass('mediaReference'))->find($id);
    }

    public function findMediaReferencesByIds(array $ids)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('r, m')
            ->from($this->factory->getClass('mediaReference'), 'r')
            ->leftJoin('r.media', 'm')
            ->where($qb->expr()->in('r.id', $ids))
        ;

        return $qb->getQuery()->getResult();
    }
}