<?php

namespace Ano\Bundle\MediaBundle\Model;

use \DateTime;

abstract class MediaReference implements MediaReferenceInterface
{
    /* @var Media */
    protected $media;

    /* @var MediaGroupInterface */
    protected $group;

    /* @var integer */
    protected $position = 0;

    /* @var DateTime */
    protected $createdAt;

    /* @var DateTime */
    protected $updatedAt;

    /* @var boolean */
    protected $enabled = true;

    /**
     * Constructor
     */
    public function __construct(Media $media, MediaGroupInterface $group)
    {
        $this->updatedAt = $this->createdAt = new DateTime();
        $this->setMedia($media);
        $this->setGroup($group);
    }

    /**
     * {@inheritDoc}
     */
    public function setMedia(Media $media)
    {
        $this->media = $media;
    }

    /**
     * {@inheritDoc}
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * {@inheritDoc}
     */
    public function setGroup(MediaGroupInterface $group)
    {
        $this->group = $group;
    }

    /**
     * {@inheritDoc}
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = (int) $position;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function isEnabled()
    {
        return $this->getEnabled();
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}