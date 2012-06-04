<?php

namespace Ano\Bundle\MediaBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

abstract class MediaGroup implements MediaGroupInterface
{
    /* @var string */
    protected $name;

    /* @var MediaReference[] */
    protected $mediaReferences;

    /* @var boolean */
    protected $enabled = true;

    /* @var \DateTime */
    protected $createdAt;

    /* @var \DateTime */
    protected $updatedAt;


    public function __construct($name = null)
    {
        $this->setName($name);
        $this->createdAt = $this->updatedAt = new \DateTime();
        $this->mediaReferences = new ArrayCollection();
    }

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
        $this->mediaReferences->add($mediaReference);
        $mediaReference->setGroup($this);

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
     * @param $mediaReferences[]|ArrayCollection $mediaReferences
     */
    public function setMediaReferences($mediaReferences)
    {
        $this->mediaReferences = $mediaReferences;
        foreach($mediaReferences as $ref) {
            $ref->setGroup($this);
        }
    }

    /**
     * @param MediaReferenceInterface $mediaReference
     */
    public function removeMediaReference(MediaReferenceInterface $mediaReference)
    {
        $this->mediaReferences->removeElement($mediaReference);
        $mediaReference->setGroup(null);
    }

    public function hasMediaReference(MediaReferenceInterface $mediaReference)
    {
        return $this->mediaReferences->contains($mediaReference);
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