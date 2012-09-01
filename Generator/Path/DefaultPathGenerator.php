<?php

namespace Ano\Bundle\MediaBundle\Generator\Path;

use Ano\Bundle\MediaBundle\Model\Media;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

class DefaultPathGenerator implements PathGeneratorInterface
{
    public function generatePath(Media $media, $format = null)
    {
        $guesser = ExtensionGuesser::getInstance();

        if (empty($format)) {
            return sprintf(
                '%s/%s.%s',
                $media->getContext(),
                $media->getUuid(),
                $guesser->guess($media->getContentType())
            );
        }

        return sprintf(
            '%s/%s_%s.%s',
            $media->getContext(),
            $media->getUuid(),
            $format,
            $guesser->guess($media->getContentType())
        );
    }
}