<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\Media;
use Ano\Bundle\MediaBundle\Model\MediaReferenceInterface;

interface MediaRepositoryInterface
{
    public function find($id);

    public function save(Media $media);

    public function delete(Media $media);

    public function findMediaReference($id);

    public function findMediaReferencesByIds(array $ids);
}