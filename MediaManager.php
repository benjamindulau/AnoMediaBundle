<?php

namespace Ano\Bundle\MediaBundle;

use Ano\Bundle\MediaBundle\Model\Media;
use Ano\Bundle\MediaBundle\Model\MediaContext;
use Ano\Bundle\MediaBundle\Cdn\CdnInterface;
use Ano\Bundle\MediaBundle\Provider\ProviderInterface;
use Gaufrette\Filesystem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ano\Bundle\MediaBundle\MediaEvents;
use Ano\Bundle\MediaBundle\Event\MediaEvent;

class MediaManager
{
    /* @var array */
    protected $contexts = array();

    /* @var array */
    protected $providers = array();

    /* @var ProviderInterface */
    protected $defaultProvider;

    /* @var array */
    protected $cdns = array();

    /* @var CdnInterface */
    protected $defaultCdn;

    /* @var array */
    protected $filesystems = array();

    /* @var Filesystem */
    protected $defaultFilesystem;

    /* @var EventDispatcherInterface */
    protected $dispatcher;


    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }


    /**
     * @param string       $name
     * @param MediaContext $context
     * @return void
     */
    public function addContext($name, MediaContext $context)
    {
        $this->contexts[$name] = $context;
    }

    /**
     * @param string $name
     * @return \Ano\Bundle\MediaBundle\Model\MediaContext
     */
    public function getContext($name)
    {
        if (!$this->hasContext($name)) {
            throw new \InvalidArgumentException(sprintf('Context "%s" doesn\'t exist', $name));
        }

        return $this->contexts[$name];
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function hasContext($name)
    {
        return array_key_exists($name, $this->contexts);
    }

    /**
     * @return array of MediaContext
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * @param array $cdns
     */
    public function setCdns(array $cdns)
    {
        $this->cdns = $cdns;
    }

    /**
     * @return array
     */
    public function getCdns()
    {
        return $this->cdns;
    }

    public function addCdn($name, CdnInterface $cdn)
    {
        $this->cdns[$name] = $cdn;
    }

    public function getCdn($name)
    {
        if (!$this->hasCdn($name)) {
            throw new \InvalidArgumentException(sprintf('Cdn "%s" doesn\'t exist', $name));
        }

        return $this->cdns[$name];
    }

    public function hasCdn($name)
    {
        return array_key_exists($name, $this->cdns);
    }

    /**
     * @param CdnInterface $defaultCdn
     */
    public function setDefaultCdn(CdnInterface $defaultCdn)
    {
        $this->defaultCdn = $defaultCdn;
    }

    /**
     * @return CdnInterface
     */
    public function getDefaultCdn()
    {
        return $this->defaultCdn;
    }

    /**
     * @param ProviderInterface
     */
    public function setDefaultProvider(ProviderInterface $defaultProvider)
    {
        $this->defaultProvider = $defaultProvider;
    }

    /**
     * @return ProviderInterface
     */
    public function getDefaultProvider()
    {
        return $this->defaultProvider;
    }

    /**
     * @param array $providers
     */
    public function setProviders($providers)
    {
        $this->providers = $providers;
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    public function addProvider($name, ProviderInterface $provider)
    {
        $this->providers[$name] = $provider;
    }

    public function getProvider($name)
    {
        if (!$this->hadProvider($name)) {
            throw new \InvalidArgumentException(sprintf('Provider "%s" doesn\'t exist', $name));
        }

        return $this->providers[$name];
    }

    public function hasProvider($name)
    {
        return array_key_exists($name, $this->providers);
    }

    /**
     * @param Filesystem
     */
    public function setDefaultFilesystem(Filesystem $defaultFilesystem)
    {
        $this->defaultFilesystem = $defaultFilesystem;
    }

    /**
     * @return Filesystem
     */
    public function getDefaultFilesystem()
    {
        return $this->defaultFilesystem;
    }

    /**
     * @param array $filesystems
     */
    public function setFilesystems($filesystems)
    {
        $this->filesystems = $filesystems;
    }

    /**
     * @return array
     */
    public function getFilesystems()
    {
        return $this->filesystems;
    }

    public function addFilesystem($name, Filesystem $filesystem)
    {
        $this->filesystems[$name] = $filesystem;
    }

    public function getFilesystem($name)
    {
        if (!$this->hadFilesystem($name)) {
            throw new \InvalidArgumentException(sprintf('Filesystem "%s" doesn\'t exist', $name));
        }

        return $this->filesystems[$name];
    }

    public function hasFilesystem($name)
    {
        return array_key_exists($name, $this->filesystems);
    }

    public function prepareMedia(Media $media)
    {
        $event = new MediaEvent($media);
        $this->dispatcher->dispatch(MediaEvents::BEFORE_PREPARE, $event);

        $context = $this->getContext($media->getContext());
        $context->getProvider()->prepareMedia($media);

        $this->dispatcher->dispatch(MediaEvents::AFTER_PREPARE, $event);
    }

    public function saveMedia(Media $media, $new = false)
    {
        $context = $this->getContext($media->getContext());
        $context->getProvider()->setFormats($context->getFormats());
        $event = new MediaEvent($media);

        if ($new) {
            $this->dispatcher->dispatch(MediaEvents::BEFORE_SAVE, $event);

            $context->getProvider()->saveMedia($media);

            $this->dispatcher->dispatch(MediaEvents::AFTER_SAVE, $event);
        }
        else {
            $this->dispatcher->dispatch(MediaEvents::BEFORE_UPDATE, $event);

            $context->getProvider()->updateMedia($media);

            $this->dispatcher->dispatch(MediaEvents::AFTER_UPDATE, $event);
        }
    }

    public function removeMedia(Media $media)
    {
        $event = new MediaEvent($media);
        $this->dispatcher->dispatch(MediaEvents::BEFORE_REMOVE, $event);

        $context = $this->getContext($media->getContext());
        $context->getProvider()->setFormats($context->getFormats());
        $context->getProvider()->removeMedia($media);

        $this->dispatcher->dispatch(MediaEvents::AFTER_REMOVE, $event);
    }

    public function getUri(Media $media, $format = null, array $options = array())
    {
        $context  = $this->getContext($media->getContext());
        $provider = $context->getProvider();
        $options  = $provider->getRenderOptions($media, $format, $options);

        return $provider->renderRaw($media, $format, $options);
    }
}
