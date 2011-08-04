<?php

namespace Ano\Bundle\MediaBundle\Provider;

use Ano\Bundle\MediaBundle\Model\Media;
use Ano\Bundle\MediaBundle\Util\Image\ImageManipulatorInterface;
use Symfony\Component\HttpFoundation\File\File;
use Ano\Bundle\SystemBundle\HttpFoundation\File\MimeType\ExtensionGuesser;

class ImageProvider extends AbstractProvider
{
    /* @var \Ano\Bundle\MediaBundle\Util\Image\ImageManipulatorInterface */
    protected $imageManipulator;

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
        if (!$media->getContent() instanceof File) {
            return;
        }
        
        $this->generateFormats($media);
    }

    public function generateFormats(Media $media)
    {
        foreach($this->formats as $format => $options) {
            $width = array_key_exists('width', $options) ? $options['width'] : null;
            $height = array_key_exists('height', $options) ? $options['height'] : null;

            $this->imageManipulator->resize(
                $this->filesystem->get($media->getContent()->getRealPath()),
                $this->filesystem->get($this->generateRelativePath($media, $format), true),
                $width,
                $height
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaUrl(Media $media, $format)
    {
        $path = $this->generateRelativePath($media, $format);

        return $this->cdn->getFullPath($path);
    }

    public function generateRelativePath(Media $media, $format)
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
    public function updateMedia(Media $media)
    {
        $this->saveMedia($media);
    }

    /**
     * {@inheritDoc}
     */
    public function removeMedia(Media $media)
    {
        foreach($this->formats as $format => $options) {
            $path = $this->generateRelativePath($media, $format);
            if ($this->getFilesystem()->has($path)) {
                $this->getFilesystem()->delete($path);
            }
        }
    }

    public function setImageManipulator(ImageManipulatorInterface $imageManipulator)
    {
        $this->imageManipulator = $imageManipulator;
    }
}