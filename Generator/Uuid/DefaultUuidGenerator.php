<?php

namespace Ano\Bundle\MediaBundle\Generator\Uuid;

use Ano\Bundle\MediaBundle\Model\Media;

class DefaultUuidGenerator implements UuidGeneratorInterface
{
    public function generateUuid(Media $media)
    {
        return uniqid();
    }
}