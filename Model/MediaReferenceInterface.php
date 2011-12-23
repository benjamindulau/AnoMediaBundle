<?php

namespace Ano\Bundle\MediaBundle\Model;

interface MediaReferenceInterface
{
    /**
     * Sets the targeted Media
     *
     * @param Media $media
     */
    function setMedia(Media $media);

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
    function setGroup(MediaGroupInterface $group);

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
}