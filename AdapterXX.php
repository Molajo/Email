<?php
/**
 * Adapter for Email
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email;

defined('MOLAJO') or die;

use Molajo\Email\Api\EmailInterface;

use Molajo\Email\Exception\EmailException;

/**
 * Adapter for Email
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @since     1.0
 */
class Adapter implements EmailInterface
{
    /**
     * Email Type
     *
     * @var     object
     * @since   1.0
     */
    public $et;

    /**
     * Construct
     *
     * @param   string $email_type
     * @param   string $filesystem_instance
     *
     * @since   1.0
     * @throws  EmailException
     */
    public function __construct($email_type = '', $email_class = '', $options = array())
    {
        if ($email_type == '') {
            $email_type = 'PhpMailer';
        }

        $this->getEmailType($email_type, $email_class, $options);
    }

    /**
     * Get the Email Type (Css, Js, Links, Metadata)
     *
     * @param   string $email_type
     *
     * @return  $this
     * @since   1.0
     * @throws  EmailException
     */
    protected function getEmailType($email_type, $email_class = '', $options = array())
    {
        if ($email_type == '') {
            $email_type = 'PhpMailer';
        }

        $class = 'Molajo\\Email\\Type\\' . $email_type;

        if (class_exists($class)) {
        } else {
            throw new EmailException
            ('Email Type class ' . $class . ' does not exist.');
        }

        $this->et = new $class($email_class, $options);

        return $this;
    }

    /**
     * Set the Email Type (PhpMailer, Swiftmailer)
     *
     * @param   string  $email_type
     * @param   array() $options
     *
     * @return  $this
     * @since   1.0
     * @throws  EmailException
     */
    public function setEmailPackage($email_class, $options = array())
    {
        return $this->et->setEmailPackage($email_class, $options);
    }

    /**
     * Get Property
     *
     * @param   string $key
     * @param   mixed  $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function get($key, $default)
    {
        return $this->et->get($key, $default);
    }

    /**
     * Set Property
     *
     * @param   string $key
     * @param   array  $options
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    public function set($key, $options = array())
    {
        return $this->et->set($key, $options);
    }

    /**
     * Send email
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function send()
    {
        return $this->et->send();
    }
}
