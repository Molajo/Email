<?php
/**
 * Connection to Email Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email;

defined('MOLAJO') or die;

use Exception;
use Molajo\Email\Exception\ConnectionException;
use Molajo\Email\Api\ConnectionInterface;

/**
 * Connection to Email Adapter
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Connection implements ConnectionInterface
{
    /**
     * Adapter Instance
     *
     * @var     object
     * @since   1.0
     */
    public $adapter;

    /**
     * Adapter Handler Instance
     *
     * @var     object
     * @since   1.0
     */
    public $adapter_handler;

    /**
     * Constructor
     *
     * @param   string $adapter_handler
     * @param   array  $options
     *
     * @since   1.0
     * @api
     */
    public function __construct($adapter_handler = 'File', $options = array())
    {
        if ($adapter_handler == '') {
            $adapter_handler = 'File';
        }

        $this->getAdapterHandler($adapter_handler);
        $this->getAdapter($adapter_handler);
        $this->connect($options);
    }

    /**
     * Get the Email specific Adapter Handler
     *
     * @param   string $adapter_handler
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     * @api
     */
    protected function getAdapterHandler($adapter_handler = 'File')
    {
        $class = 'Molajo\\Email\\Handler\\' . $adapter_handler;

        try {
            $this->adapter_handler = new $class($adapter_handler);

        } catch (Exception $e) {
            throw new ConnectionException
            ('Email: Could not instantiate Email Adapter Handler: ' . $adapter_handler);
        }

        return;
    }

    /**
     * Get Email Adapter, inject with specific Email Adapter Handler
     *
     * @param   string $adapter_handler
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     * @api
     */
    protected function getAdapter($adapter_handler)
    {
        $class = 'Molajo\\Email\\Adapter';

        try {
            $this->adapter = new $class($this->adapter_handler);

        } catch (Exception $e) {
            throw new ConnectionException
            ('Email: Could not instantiate Adapter for Email Type: ' . $adapter_handler);
        }

        return $this;
    }

    /**
     * Connect to the Email Type
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     * @api
     */
    public function connect($options = array())
    {
        try {
            $this->adapter->connect($options);

        } catch (Exception $e) {

            throw new ConnectionException
            ('Email: Caught Exception: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Facade: pass adapter calls on so it can route to the adapter handler ...
     *
     * @param   string $name
     * @param   array  $arguments
     *
     * @return  mixed
     * @since   1.0
     * @throws  ConnectionException
     */
    public function __call($name, $arguments)
    {
        if ($name == 'get') {
            $key = false;
            if (isset($arguments[0])) {
                $key = $arguments[0];
            }

            return $this->adapter->get($key);

        } elseif ($name == 'set') {

            $key = false;
            if (isset($arguments[0])) {
                $key = $arguments[0];
            }

            $value = null;
            if (isset($arguments[1])) {
                $value = $arguments[1];
            }

            $ttl = 0;
            if (isset($arguments[1])) {
                $ttl = $arguments[1];
            }

            return $this->adapter->set($key, $value, $ttl);

        } elseif ($name == 'remove') {

            $key = false;
            if (isset($arguments[0])) {
                $key = $arguments[0];
            }

            return $this->adapter->remove($key);

        } elseif ($name == 'clear') {

            return $this->adapter->clear();

        } elseif ($name == 'getMultiple') {

            $keys   = array();
            if (isset($arguments[0])) {
                $keys = $arguments[0];
            }

            return $this->adapter->getMultiple($keys);

        } elseif ($name == 'setMultiple') {

            $items   = array();
            $items[] = false;
            if (isset($arguments[0])) {
                $items = $arguments[0];
            }

            $ttl = null;
            if (isset($arguments[1])) {
                $ttl = $arguments[1];
            }
            return $this->adapter->setMultiple($items, $ttl);

        } elseif ($name == 'removeMultiple') {

            $keys   = array();
            $keys[] = false;
            if (isset($arguments[0])) {
                $keys = $arguments[0];
            }
            return $this->adapter->removeMultiple($keys);

        } else {
            throw new ConnectionException
            ('Email Connection: Method not known: ' . $name);
        }
    }

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     * @api
     */
    public function close()
    {
        $this->adapter->close();

        return $this;
    }
}
