<?php

namespace Ano\Bundle\MediaBundle\Twig\Extension;

use Ano\Bundle\MediaBundle\MediaManager;
use Ano\Bundle\MediaBundle\Model\Media;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Benjamin Dulau <benjamin.dulau@anonymation.com>
 */
class MediaExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'media' => new \Twig_Function_Method($this, 'getMedia'),
        );
    }

    public function getMedia(Media $media, $format = null, array $options = array())
    {
        $context = $this->getMediaManager()->getContext($media->getContext());
        $provider = $context->getProvider();
        $options = $provider->getRenderOptions($media, $format, $options);
        
        if (null == $provider->getTemplate()) {
            return $provider->renderRaw($media, $format, $options);
        }

        return $this->container->get('templating')->render($provider->getTemplate(), array(
            'media' => $media,
            'format' => $format,
            'options' => $options,
        ));
    }

    /**
     * @return \Ano\Bundle\MediaBundle\MediaManager
     */
    public function getMediaManager()
    {
        return $this->container->get('ano_media.manager');
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'media';
    }
}
