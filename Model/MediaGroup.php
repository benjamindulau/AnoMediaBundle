<?php

namespace Ano\Bundle\MediaBundle\Model;

abstract class MediaGroup implements MediaGroupInterface
{
    /* @var string */
    protected $name;

    /* @var MediaReference[] */
    protected $mediaReferences = array();

    /* @var boolean */
    protected $enabled = true;

    /* @var \DateTime */
    protected $createdAt;

    /* @var \DateTime */
    protected $updatedAt;


    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     *
     * @return MediaGroup Fluent Interface
     */
    public function addMediaReference(MediaReferenceInterface $mediaReference)
    {
        $this->mediaReferences[] = $mediaReference;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaReferences()
    {
        return $this->mediaReferences;
    }

    /**
     * {@inheritDoc}
     */
    public function isEnabled()
    {
        return $this->getEnabled();
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}