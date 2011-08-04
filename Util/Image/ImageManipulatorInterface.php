<?php

namespace Ano\Bundle\MediaBundle\Util\Image;

use Gaufrette\File;

interface ImageManipulatorInterface
{
    const RESIZE_MODE_OUTBOUND = 'outbound';
    const RESIZE_MODE_INSET = 'inset';

    public function resize(File $fromFile, File $toFile, $width, $height, $mode = self::RESIZE_MODE_OUTBOUND, $options = array());
}