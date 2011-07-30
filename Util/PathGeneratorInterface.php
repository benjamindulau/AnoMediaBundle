<?php

namespace Ano\Bundle\MediaBundle\Util;

use Ano\Bundle\MediaBundle\Model\Media;

interface PathGeneratorInterface
{
    public function generatePath(Media $media);
}