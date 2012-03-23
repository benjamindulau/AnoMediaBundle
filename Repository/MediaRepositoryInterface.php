<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\Media;
use Ano\Bundle\MediaBundle\Model\MediaReferenceInterface;

interface MediaRepositoryInterface
{
    public function save(Media $media);

    public function delete(Media $media);
}