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

    /** @var string */
    protected $containerName;

    /** @var \CF_Container */
    protected $container;

    /** @var float */
    protected $version;

    public function __construct(\CF_Authentication $authentication, $containerName = null, $version = 1)
    {
        $this->authentication = $authentication;
        $this->containerName = $containerName;
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

        $this->connection = new \CF_Connection($this->authentication);
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
        $object = $this->getContainer()->get_object($key);

        try {
            return $object->read();
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Could not read the \'%s\' file.', $key));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content, array $metadata = null)
    {
        $object = $this->tryGetObject($key);
        if (false === $object) {
            // the object does not exist, so we create it
            $object = $this->getContainer()->create_object($key);
        }

        if (!$object->write($content)) {
            throw new \RuntimeException(sprintf('Could not write the \'%s\' file.', $key));
        }

        return Util\Size::fromContent($content);
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        return $this->getContainer()->list_objects(0, null, null);
    }

    /**
     * {@inheritDoc}
     */
    public function checksum($key)
    {
        $object = $this->getContainer()->get_object($key);

        return $object->getETag();
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        try {
            $this->getContainer()->delete_object($key);
        } catch (\NoSuchObjectException $e) {
            // @todo what do we do when the object does not exist?
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Could not delete the \'%s\' file.', $key));
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
        try {
            return $this->getContainer()->get_object($key);
        } catch (\NoSuchObjectException $e) {
            // the NoSuchObjectException is thrown by the CF_Object during it's
            // creation if the object doesn't exist
            return false;
        }
    }
}
