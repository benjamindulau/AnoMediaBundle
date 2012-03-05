<?php

namespace Ano\Bundle\MediaBundle\Provider;

use Ano\Bundle\MediaBundle\Model\Media;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Ano\Bundle\SystemBundle\HttpFoundation\File\MimeType\ExtensionGuesser;

class FileProvider extends AbstractProvider
{
    /* @var string */
    protected $template = null;

    /**
     * {@inheritDoc}
     */
    public function prepareMedia(Media $media)
    {
        $content = $media->getContent();
        if (empty($content)) {
            return false;
        }

        if (!$content instanceof File) {
            if (!is_file($content)) {
                throw new \RuntimeException('Invalid file');
            }

            $media->setContent(new File($content));
        }

        if (null == $media->getUuid()) {
            $uuid = $this->uuidGenerator->generateUuid($media);
            $media->setUuid($uuid);
        }

        $metadata = array();

        $media->setMetadata($metadata);
        //$media->setName($media->getContent()->getBasename());
        $media->setContentType($media->getContent()->getMimeType());

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function saveMedia(Media $media)
    {
        if (!$media->getContent() instanceof File) {
            return false;
        }

        $originalFile = $this->getOriginalFile($media);
        $originalFile->setContent(file_get_contents($media->getContent()->getRealPath()));

        return true;
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

    public function getOriginalFilePath(Media $media)
    {
        return sprintf(
            '%s/%s.%s',
            $this->generatePath($media),
            $media->getUuid(),
            ExtensionGuesser::guess($media->getContentType())
        );
    }

    public function getOriginalFile(Media $media)
    {
        return $this->getFilesystem()->get($this->getOriginalFilePath($media), true);
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

    /**
     * {@inheritDoc}
     */
    public function getRenderOptions(Media $media, $format, array $options = array())
    {
        return $options;
    }
}