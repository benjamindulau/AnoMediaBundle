<?php

namespace Ano\Bundle\MediaBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Ano\Bundle\MediaBundle\Model\Media;

class MediaEvent extends Event
{
    protected $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    /**
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }
}