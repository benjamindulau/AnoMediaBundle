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

    /** @var boolean */
    protected $markedAsDeleted = false;

    /**
     * Constructor
     */
    public function __construct(Media $media = null, MediaGroupInterface $group = null)
    {
        $this->updatedAt = $this->createdAt = new DateTime();
        if (null !== $media) {
            $this->setMedia($media);
        }

        if (null !== $group) {
            $this->setGroup($group);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setMedia(Media $media = null)
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
    public function setGroup(MediaGroupInterface $group = null)
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

    /**
     * @param boolean $markedAsDeleted
     */
    public function setMarkedAsDeleted($markedAsDeleted)
    {
        $this->markedAsDeleted = $markedAsDeleted;
    }

    /**
     * @return boolean
     */
    public function getMarkedAsDeleted()
    {
        return $this->markedAsDeleted;
    }

    public function markAsDeleted()
    {
        $this->setMarkedAsDeleted(true);
    }

    public function isMarkedAsDeleted()
    {
        return $this->getMarkedAsDeleted();
    }
}