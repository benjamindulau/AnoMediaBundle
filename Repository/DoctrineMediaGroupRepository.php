<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;
use Ano\Bundle\MediaBundle\Model\Factory;
use Doctrine\ORM\EntityManager;

class DoctrineMediaGroupRepository implements MediaGroupRepositoryInterface
{
    protected $entityManager;
    protected $factory;

    public function __construct(EntityManager $entityManager, Factory $factory)
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    public function save(MediaGroupInterface $mediaGroup)
    {
        try {
//            $this->entityManager->beginTransaction();
        // TODO: See if it's relevant to restore this try/catch block.
        // When rolling back operations, the entity manager is closed which
        // causes the EM to be unusable in the further persistence operations
        // INVESTIGATE !!
        // @see: http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/transactions-and-concurrency.html#exception-handling

//            $this->fixMediaReferences($mediaGroup);
            $this->entityManager->persist($mediaGroup);
            $this->entityManager->flush();
        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }

    private function fixMediaReferences(MediaGroupInterface $mediaGroup)
    {
        $references = $mediaGroup->getMediaReferences();

        foreach($references as $ref) {
            $this->entityManager->persist($ref);
            $media = $this->entityManager->merge($ref->getMedia());

            if (null === $ref->getId()) {
                $ref->setGroup($mediaGroup);
                $ref->setMedia($media);
                $this->entityManager->persist($ref);
            } else {
                $ref = $this->entityManager->merge($ref);
                $ref->setMedia($media);
//                $this->entityManager->persist($media);

                if ($ref->isMarkedAsDeleted()) {
                    $mediaGroup->removeMediaReference($ref);
                    $this->entityManager->remove($ref);
                } else {
                    $this->entityManager->persist($ref);
                }
            }
        }
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