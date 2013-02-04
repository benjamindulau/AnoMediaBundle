<?php

namespace Ano\Bundle\MediaBundle\Gaufrette\Adapter;

use Gaufrette\Util;
use Gaufrette\Adapter\RackspaceCloudfiles as BaseRackspaceCloudfiles;

/**
 * Rackspace cloudfiles adapter
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class RackspaceCloudfiles extends BaseRackspaceCloudfiles
{
    /** @var \CF_Authentication */
    protected $authentication;

    /** @var \CF_Connection */
    protected $connection;

    /** @var boolean */
    protected $snet;

    /** @var string */
    protected $containerName;

    /** @var \CF_Container */
    protected $container;

    protected $basePath;

    /** @var float */
    protected $version;

    public function __construct(\CF_Authentication $authentication, $containerName = null, $basePath = '', $snet = false, $version = 1)
    {
        $this->authentication = $authentication;
        $this->containerName = $containerName;
        $this->basePath = $basePath;
        $this->snet = $snet;
        $this->version = $version;
    }

    protected function authenticate()
    {
        $this->authentication->authenticate();
    }

    protected function connect()
    {
        if ($this->connection instanceof \CF_Connection) {
            return $this->connection;
        }

        if (!$this->authentication->authenticated()) {
            $this->authenticate();
        }

        $this->connection = new \CF_Connection($this->authentication, $this->snet);
    }

    /**
     * @return \CF_Container
     */
    protected function getContainer()
    {
        if (!$this->connection instanceof \CF_Connection) {
            $this->connect();
        }

        return $this->connection->get_container($this->containerName);
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        $realKey = $this->composeKey($key);
        $object = $this->getContainer()->get_object($realKey);

        try {
            return $object->read();
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Could not read the \'%s\' file.', $realKey));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content, array $metadata = null)
    {
        $realKey = $this->composeKey($key);
        $object = $this->tryGetObject($realKey);
        if (false === $object) {
            // the object does not exist, so we create it
            $object = $this->getContainer()->create_object($realKey);
        }

        if (!$object->write($content)) {
            throw new \RuntimeException(sprintf('Could not write the \'%s\' file.', $realKey));
        }

        return Util\Size::fromContent($content);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        return false !== $this->tryGetObject($this->composeKey($key));
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        $path = empty($this->basePath) ? null : $this->basePath;

        return $this->getContainer()->list_objects(0, null, null, $path);
    }

    /**
     * {@inheritDoc}
     */
    public function checksum($key)
    {
        $object = $this->getContainer()->get_object($this->composeKey($key));

        return $object->getETag();
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $realKey = $this->composeKey($key);
        try {
            $this->getContainer()->delete_object($realKey);
        } catch (\NoSuchObjectException $e) {
            // @todo what do we do when the object does not exist?
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Could not delete the \'%s\' file.', $realKey));
        }
    }

    /**
     * Tries to get the object for the specified key or return false
     *
     * @param  string $key The key of the object
     *
     * @return \CF_Object or FALSE if the object does not exist
     */
    protected function tryGetObject($key)
    {
        $realKey = $this->composeKey($key);
        try {
            return $this->getContainer()->get_object($realKey);
        } catch (\NoSuchObjectException $e) {
            // the NoSuchObjectException is thrown by the CF_Object during it's
            // creation if the object doesn't exist
            return false;
        }
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function composeKey($key)
    {
        return empty($this->basePath) ? $key : sprintf('%s/%s', rtrim($this->basePath), $key);
    }
}
