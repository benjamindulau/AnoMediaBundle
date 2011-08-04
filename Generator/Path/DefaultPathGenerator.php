<?php

namespace Ano\Bundle\MediaBundle\Generator\Path;

use Ano\Bundle\MediaBundle\Model\Media;

class DefaultPathGenerator implements PathGeneratorInterface
{
    public function generatePath(Media $media)
    {
        return sprintf('%s', $media->getContext());
    }

}