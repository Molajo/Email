<?php
/**
 * Email
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email;

use CommonApi\Email\EmailInterface;

/**
 * Adapter for Email
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Driver implements EmailInterface
{
    /**
     * Email Adapter Adapter
     *
     * @var     object
     * @since   1.0
     */
    protected $adapter;

    /**
     * Constructor
     *
     * @param  EmailInterface $email
     *
     * @since  1.0
     */
    public function __construct(EmailInterface $email)
    {
        if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get')) {
            date_default_timezone_set(@date_default_timezone_get());
        }

        $this->adapter = $email;
    }

    /**
     * Return parameter value or default
     *
     * @param   string $key
     * @param   string $default
     *
     * @return  mixed
     * @since   1.0
     */
    public function get($key, $default = null)
    {
        return $this->adapter->get($key, $default);
    }

    /**
     * Set parameter value
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     */
    public function set($key, $value = null)
    {
        $this->adapter->set($key, $value);

        return $this;
    }

    /**
     * Send Email
     *
     * @return  mixed
     * @since   1.0
     */
    public function send()
    {
        $this->adapter->send();

        return $this;
    }
}
