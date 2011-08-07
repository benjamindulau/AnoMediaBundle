<?php

namespace Ano\Bundle\MediaBundle\Provider;

use Ano\Bundle\MediaBundle\Model\Media;
use Symfony\Component\HttpFoundation\File\File;
use Ano\Bundle\SystemBundle\HttpFoundation\File\MimeType\ExtensionGuesser;

abstract class AbstractVideoProvider extends AbstractProvider
{
    abstract protected function getMetadata(Media $media);

    /**
     * {@inheritDoc}
     */
    public function prepareMedia(Media $media)
    {
        if (null == $media->getUuid()) {
            $uuid = $this->uuidGenerator->generateUuid($media);
            $media->setUuid($uuid);
        }

        $content = $media->getContent();
        if (empty($content)) {
            return;
        }

        $media->setContentType('video/x-flv');
        $media->setUpdatedAt(new \DateTime());
    }

    /**
     * {@inheritDoc}
     */
    public function saveMedia(Media $media)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function updateMedia(Media $media)
    {
        $this->saveMedia($media);
    }


    /**
     * {@inheritDoc}
     */
    public function removeMedia(Media $media)
    {
        
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaUrl(Media $media, $format = null)
    {
        $path = $this->generateRelativePath($media, $format);

        return $this->cdn->getFullPath($path);
    }

    public function generateRelativePath(Media $media, $format = null)
    {
        return sprintf(
            '%s/%s_%s.%s',
            $this->generatePath($media),
            $media->getUuid(),
            $format,
            ExtensionGuesser::guess($media->getContentType())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function renderRaw(Media $media, $format = null, array $options = array())
    {
        return $this->getMediaUrl($media, $format);
    }


}