<?php

namespace Ano\Bundle\MediaBundle\Util;

use Ano\Bundle\MediaBundle\Model\Media;

interface UuidGeneratorInterface
{
    public function generateUuid(Media $media);
}