<?php

namespace Ano\Bundle\MediaBundle\Model;

abstract class Media
{
    /* @var string Universal Unique ID */
    protected $uuid;

    /* @var string */
    protected $name;

    /* @var string */
    protected $context;

    /* @var string */
    protected $contentType;

    /**
     * @var array The metadata of the media,
     *            can be very different depending on the Media type
     */
    protected $metadata;

    /* @var integer */
    protected $width;

    /* @var integer */
    protected $height;

    /* @var \DateTime */
    protected $createdAt;

    /* @var \DateTime */
    protected $updatedAt;

    /* @var mixed */
    protected $content;

    /* @var MediaReferenceInterface[] */
    protected $mediaReferences = array();


    public function __construct($context = null)
    {
        if (null !== $context) {
            $this->setContext($context);
        }
        $this->updatedAt = $this->createdAt = new \DateTime();
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
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
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
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

    /**
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return MediaReferenceInterface[]
     */
    public function getMediaReferences()
    {
        return $this->mediaReferences;
    }

    /**
     * @param MediaReferenceInterface[]
     */
    public function setMediaReferences(array $mediaReferences)
    {
        $this->mediaReferences = $mediaReferences;
    }
}