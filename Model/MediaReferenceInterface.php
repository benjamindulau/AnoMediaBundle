<?php

namespace Ano\Bundle\MediaBundle\Model;

interface MediaReferenceInterface
{
    /**
     * Sets the targeted Media
     *
     * @param Media $media
     */
    function setMedia(Media $media = null);

    /**
     * Gets the targeted Media
     *
     * @return Media
     */
    function getMedia();

    /**
     * Sets the media group
     *
     * @param MediaGroupInterface $group
     */
    function setGroup(MediaGroupInterface $group = null);

    /**
     * Gets the media group
     *
     * @return MediaGroupInterface
     */
    function getGroup();

    /**
     * Returns whether the Media reference is active or not
     *
     * @return boolean
     */
    function isEnabled();

    /**
     * Returns true if the reference should be removed during persistence operations
     *
     * @return bool
     */
    function isMarkedAsDeleted();
}