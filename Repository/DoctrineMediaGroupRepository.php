<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;
use Ano\Bundle\MediaBundle\Model\Factory;
use Doctrine\ORM\EntityManager;

class DoctrineMediaGroupRepository implements MediaGroupRepositoryInterface
{
    private $entityManager;
    private $factory;

    public function __construct(EntityManager $entityManager, Factory $factory)
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    public function save(MediaGroupInterface $mediaGroup)
    {
        try {
            $this->entityManager->beginTransaction();

            $this->fixMediaReferences($mediaGroup);
            $this->entityManager->persist($mediaGroup);
            $this->entityManager->flush();
            $this->deleteOldMediaReferencesForGroup($mediaGroup);

            $this->entityManager->commit();
        } catch(\Exception $e) {
            $this->entityManager->rollback();
        }

    }

    private function fixMediaReferences(MediaGroupInterface $mediaGroup)
    {
        $references = clone ($mediaGroup->getMediaReferences());

        $ids = array();
        foreach($references as $ref) {
            $newMedia = $this->entityManager->merge($ref->getMedia());
            $ref->setMedia($newMedia);

            if (null !== $ref->getId()) {
                $ref = $this->entityManager->merge($ref);
                $ids[] = $ref->getId();
            }
        }

        $mediaGroup->setMediaReferences($references);
    }

    protected function deleteOldMediaReferencesForGroup(MediaGroupInterface $mediaGroup)
    {
        $ids = array();
        foreach($mediaGroup->getMediaReferences() as $references) {
            $ids[] = $references->getId();
        }

        // HACK !!
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->delete($this->factory->getClass('mediaReference'), 'mr')
            ->where('mr.group = :group')
            ->setParameter('group', $mediaGroup)
        ;
        if (!empty($ids)) {
            $qb->andWhere($qb->expr()->notIn('mr.id', $ids));
        }

        $qb->getQuery()->execute();
    }

    public function findBy(array $criteria, $orderBy = array(), $limit = null, $offset = null)
    {
        return $this->getRepository('mediaGroup')->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria)
    {
        return $this->getRepository('mediaGroup')->findOneBy($criteria);
    }

    /**
     * @param string $model
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository($model)
    {
        return $this->entityManager->getRepository($this->factory->getClass($model));
    }
}