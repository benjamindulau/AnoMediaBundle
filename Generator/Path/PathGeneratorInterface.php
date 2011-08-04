<?php

namespace Ano\Bundle\MediaBundle\Generator\Path;

use Ano\Bundle\MediaBundle\Model\Media;

interface PathGeneratorInterface
{
    public function generatePath(Media $media);
}