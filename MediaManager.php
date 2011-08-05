<?php

namespace Ano\Bundle\MediaBundle;

use Ano\Bundle\MediaBundle\Model\Media;
use Ano\Bundle\MediaBundle\Model\MediaContext;
use Ano\Bundle\MediaBundle\Cdn\CdnInterface;
use Ano\Bundle\MediaBundle\Provider\ProviderInterface;
use Gaufrette\Filesystem;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

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

    /* @var \Symfony\Component\HttpKernel\Log\LoggerInterface */
    protected $logger;
    

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

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->prepareMedia($eventArgs->getEntity());
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $this->prepareMedia($eventArgs->getEntity());
    }

    private function prepareMedia($entity)
    {
        $this->logger->info(sprintf('Prepare media : %s', get_class($entity)));

        if (!$entity instanceof Media) {
            return;
        }

        $context = $this->getContext($entity->getContext());
        $context->getProvider()->prepareMedia($entity);
    }

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $this->saveMedia($eventArgs->getEntity(), true);
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->saveMedia($eventArgs->getEntity());
    }

    private function saveMedia($entity, $new = false)
    {
        if (!$entity instanceof Media) {
            return;
        }

        $context = $this->getContext($entity->getContext());
        if ($new) {
            $context->getProvider()->saveMedia($entity);
        }
        else {
            $context->getProvider()->updateMedia($entity);
        }
    }

    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if (!$entity instanceof Media) {
            return;
        }

        $context = $this->getContext($entity->getContext());
        $context->getProvider()->removeMedia($entity);
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}