<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\Media;

interface MediaRepositoryInterface
{
    public function save(Media $media);

    public function delete(Media $media);

    public function reloadMediaByUuid(Media $media);
}