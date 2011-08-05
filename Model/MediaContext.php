<?php

namespace Ano\Bundle\MediaBundle\Model;

use Ano\Bundle\MediaBundle\Provider\ProviderInterface;

class MediaContext
{
    /* @var string */
    protected $name;

    /* @var ProviderInterface */
    protected $provider;
    

    public function __construct($name)
    {
        $this->setName($name);
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
     * @param \Ano\Bundle\MediaBundle\Provider\ProviderInterface $provider
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return \Ano\Bundle\MediaBundle\Provider\ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }
}