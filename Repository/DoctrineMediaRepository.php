<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\Media;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\UnitOfWork;

class DoctrineMediaRepository implements MediaRepositoryInterface
{
    /* @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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

    /**
     * @param Media $media
     *
     * @return Media
     */
    public function reloadMediaByUuid(Media $media)
    {
        $class = ClassUtils::getClass($media);

        return $this->entityManager->getRepository($class)->findOneBy(array(
            'uuid' => $media->getUuid(),
        ));
    }


}