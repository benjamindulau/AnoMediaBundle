<?php

namespace Ano\Bundle\MediaBundle\Provider;

use Ano\Bundle\MediaBundle\Model\Media;
use Ano\Bundle\MediaBundle\Util\Image\ImageManipulatorInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Ano\Bundle\SystemBundle\HttpFoundation\File\MimeType\ExtensionGuesser;

class ImageProvider extends AbstractProvider
{
    /* @var \Ano\Bundle\MediaBundle\Util\Image\ImageManipulatorInterface */
    protected $imageManipulator;

    /* @var \Symfony\Component\HttpKernel\Log\LoggerInterface */
    protected $logger;

    /* @var string */
    protected $template = null;

    /**
     * {@inheritDoc}
     */
    public function prepareMedia(Media $media)
    {
        $this->logger->info('Prepare Media');

        if (null == $media->getUuid()) {
            $uuid = $this->uuidGenerator->generateUuid($media);
            $media->setUuid($uuid);
        }

        $content = $media->getContent();
        if (empty($content)) {
            return;
        }

        if (!$content instanceof File) {
            if (!is_file($content)) {
                throw new \RuntimeException('Invalid image file');
            }

            $media->setContent(new File($content));
        }

        $metadata = array();
        list($metadata['width'], $metadata['height']) = @getimagesize($media->getContent()->getRealPath());

        $media->setMetadata($metadata);
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

        $originalFile = $this->getOriginalFile($media);
        $originalFile->setContent(file_get_contents($media->getContent()->getRealPath()));
        
        $this->generateFormats($media);
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

        // Original
        $path = $this->getOriginalFilePath($media);
        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }
    }

    private function getOriginalFilePath(Media $media)
    {
        return sprintf(
            '%s/%s.%s',
            $this->generatePath($media),
            $media->getUuid(),
            ExtensionGuesser::guess($media->getContentType())
        );
    }

    private function getOriginalFile(Media $media)
    {
        return $this->getFilesystem()->get($this->getOriginalFilePath($media), true);
    }

    public function generateFormats(Media $media)
    {
        $originalFile = $this->getOriginalFile($media);

        foreach($this->formats as $format => $options) {
            $this->imageManipulator->resize(
                $media,
                $originalFile,
                $this->filesystem->get($this->generateRelativePath($media, $format), true),
                $options
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaUrl(Media $media, $format = null)
    {
        // wants original file
        if (null == $format) {
            $path = $this->getOriginalFilePath($media);
        }
        else {
            $path = $this->generateRelativePath($media, $format);
        }

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

    public function setImageManipulator(ImageManipulatorInterface $imageManipulator)
    {
        $this->imageManipulator = $imageManipulator;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function renderRaw(Media $media, $format = null, array $options = array())
    {
        return $this->getMediaUrl($media, $format);
    }

    /**
     * {@inheritDoc}
     */
    public function getRenderOptions(Media $media, $format, array $options = array())
    {
        return $options;
    }


}