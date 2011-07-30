<?php

namespace Ano\Bundle\MediaBundle\Provider;

use Ano\Bundle\MediaBundle\Model\Media;
use Gaufrette\File;

class ImageProvider extends AbstractProvider
{
    /**
     * {@inheritDoc}
     */
    public function prepareMedia(Media $media)
    {
        $uuid = $this->uuidGenerator->generateUuid($media);
        $media->setUuid($uuid);

        if (!$media->getContent() instanceof File) {
            if (!is_file($media->getContent())) {
                throw new \RuntimeException('Invalid image file');
            }

            $media->setContent(new File($media->getContent()));
        }

        $media->setName($media->getContent()->getBasename());
        $media->setContentType($media->getContent()->getMimeType());
    }

    /**
     * {@inheritDoc}
     */
    public function saveMedia(Media $media)
    {
        $this->generateFormats($media);
    }

    public function generateFormats(Media $media)
    {
        
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaUrl(Media $media, $format)
    {
        // TODO: Implement getMediaUrl() method.
    }

    /**
     * {@inheritDoc}
     */
    public function updateMedia(Media $media)
    {
        // TODO: Implement updateMedia() method.
    }

    /**
     * {@inheritDoc}
     */
    public function removeMedia(Media $media)
    {
        // TODO: Implement removeMedia() method.
    }


}