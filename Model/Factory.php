<?php

namespace Ano\Bundle\MediaBundle\Model;

use Ano\Bundle\MediaBundle\Model\Media;
use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;
use Ano\Bundle\MediaBundle\Model\MediaReferenceInterface;

class Factory
{
    /* @var string[] */
    protected $classMap;

    /**
     * @param string $mediaClass  The FQNS for the media class
     * @param string $mediaGroupClass The FQNS for the media group class
     * @param string $mediaReferenceClass The FQNS for the media reference class
     */
    public function __construct($mediaClass, $mediaGroupClass, $mediaReferenceClass)
    {
        $this->classMap = array(
            'media' => $mediaClass,
            'mediaGroup' => $mediaGroupClass,
            'mediaReference' => $mediaReferenceClass,
        );
    }

    /**
     * @return MediaGroupInterface
     */
    public function createMediaGroup()
    {
        $class = $this->classMap['mediaGroup'];
        $this->validateClass($class);

        return new $class();
    }

    /**
     * @return MediaReferenceInterface
     */
    public function createMediaReference()
    {
        $class = $this->classMap['mediaReference'];
        $this->validateClass($class);

        return new $class();
    }

    /**
     * @param string $model
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getClass($model)
    {
        if (!array_key_exists($model, $this->classMap)) {
            throw new \InvalidArgumentException(sprintf('class was not found in class map for model "%s"', $model));
        }

        return $this->classMap[$model];
    }

    /**
     * @param string $class
     *
     * @throws \DomainException
     */
    protected function validateClass($class)
    {
        if (!class_exists($class)) {
            throw new \DomainException(sprintf('class %s doesn\'t exist', $class));
        }
    }
}