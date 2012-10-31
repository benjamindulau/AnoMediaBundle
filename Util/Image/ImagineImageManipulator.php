<?php

namespace Ano\Bundle\MediaBundle\Util\Image;

use Ano\Bundle\MediaBundle\Model\Media;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

use Imagine\Image\ImagineInterface,
    Imagine\Image\ImageInterface,
    Imagine\Image\Box;

use Gaufrette\File;

class ImagineImageManipulator implements ImageManipulatorInterface
{
    /* @var ImagineInterface */
    protected $imagine;

    public function __construct(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    /**
     * {@inheritDoc}
     */
    public function resize(Media $media, File $fromFile, File $toFile, $options = array())
    {
        if (!array_key_exists('quality', $options)) {
            $options['quality'] = 100;
        }

        $mode = isset($options['mode']) ? $options['mode'] : self::RESIZE_MODE_OUTBOUND;
        $width = isset($options['width']) ? (int)$options['width'] : null;
        $height = isset($options['height']) ? (int)$options['height'] : null;

        if (!is_numeric($width) && !is_numeric($height)) {
            throw new \InvalidArgumentException('You must specify at least a width and/or an height value');
        }
        
        $image = $this->imagine->load($fromFile->getContent());

        if (null == $width) {
            $image = $image->resize($image->getSize()->heighten($height));
        } elseif (null == $height) {
            $image = $image->resize($image->getSize()->widen($width));
        } else {
            switch($mode) {
                case self::RESIZE_MODE_OUTBOUND:
                    $mode = ImageInterface::THUMBNAIL_OUTBOUND;
                break;

                case self::RESIZE_MODE_INSET:
                    $mode = ImageInterface::THUMBNAIL_INSET;
                break;

                default:
                    $mode = ImageInterface::THUMBNAIL_OUTBOUND;
            }
            $image->thumbnail(new Box($width, $height), $mode);
        }

        $outputContent = $image->get(ExtensionGuesser::getInstance()->guess($media->getContentType()), $options);
        $toFile->setContent($outputContent);
    }
}