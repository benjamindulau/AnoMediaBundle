<?php

namespace Ano\Bundle\MediaBundle\Provider;

use Ano\Bundle\MediaBundle\Model\Media;


interface ProviderInterface
{
    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @param array  $format
     * @return void
     */
    public function addFormat($name, array $format);

    /**
     * @param string $name
     * @return boolean
     */
    public function hasFormat($name);

    /**
     * @param string $name
     * @return array
     */
    public function getFormat($name);

    /**
     * @return array
     */
    public function getFormats();

    /**
     * @param Media $media
     * @param string $format
     * @return string
     */
    public function getMediaUrl(Media $media, $format);

    /**
     * @param \Ano\Bundle\MediaBundle\Model\Media $media
     * @return void
     */
    public function prepareMedia(Media $media);

    /**
     * @param \Ano\Bundle\MediaBundle\Model\Media $media
     * @return void
     */
    public function saveMedia(Media $media);

    /**
     * @param \Ano\Bundle\MediaBundle\Model\Media $media
     * @return void
     */
    public function updateMedia(Media $media);

    /**
     * @param \Ano\Bundle\MediaBundle\Model\Media $media
     * @return void
     */
    public function removeMedia(Media $media);
}