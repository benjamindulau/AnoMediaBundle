<?php

namespace Ano\Bundle\MediaBundle\Generator\Uuid;

use Ano\Bundle\MediaBundle\Model\Media;

interface UuidGeneratorInterface
{
    public function generateUuid(Media $media);
}