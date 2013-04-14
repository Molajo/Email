<?php
/**
 * Email Aware Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Api;

defined('MOLAJO') or die;

use Molajo\Email\Exception\EmailException;

/**
 * Email Aware Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface EmailAwareInterface
{
    /**
     * Sets a Email Instance on the object
     *
     * @param   string $key
     *
     * @return  EmailInterface $cache
     * @since   1.0
     * @throws  EmailException
     */
    public function setEmail(EmailInterface $cache);
}
