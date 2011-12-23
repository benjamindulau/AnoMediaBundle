<?php

namespace Ano\Bundle\MediaBundle\Model;

interface MediaGroupInterface
{
    /**
     * Sets the media group name
     *
     * @param string $name
     */
    function setName($name);

    /**
     * Gets the media group name
     *
     * @return string
     */
    function getName();

    /**
     * Adds a Media reference to the group
     *
     * @param MediaReferenceInterface $mediaReference
     */
    function addMediaReference(MediaReferenceInterface $mediaReference);

    /**
     * Gets the media references list for this group
     *
     * @return MediaReferenceInterface[]
     */
    function getMediaReferences();

    /**
     * Returns whether or not the media group is enabled
     *
     * @return boolean
     */
    function isEnabled();
}