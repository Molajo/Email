<?php
/**
 * Email Interface
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Api;

defined('MOLAJO') or die;

/**
 * Email Interface
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
interface EmailInterface
{
    /**
     * Connect to Email
     *
     * @param   string  $driver
     * @param   string  $host
     * @param   string  $user
     * @param   string  $password
     * @param   string  $database_name
     * @param   string  $table_prefix
     * @param   string  $namespace
     * @param   array   $database_namespace
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function connect($driver, $host, $user, $password,
        $database_name, $table_prefix, $database_namespace, $options = array());

    /**
     * get property
     *
     * @param   string $key
     * @param   null   $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function get($key, $default = null);

    /**
     * set property
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function set($key, $value);

    /**
     * Get the current query object for the current database connection
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    public function getQueryObject();

    // str_replace('#__', $this->db->getPrefix(), $this)

    /**
     * Returns a database driver compliant date format for PHP date()
     *
     * @return string The format string.
     * @since   1.0
     */
    public function getDateFormat();

    /**
     * Returns a database driver compliant value for null date
     *
     * @return string The format string.
     *
     * @since   1.0
     * @throws  EmailException
     */
    public function getNullDate();

    /**
     * Quote value
     * Alias: q
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function quote($value);

    /**
     * Quote and return Name
     * Alias: qn
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function qn($name);

    /**
     * Method to escape a string for usage in an SQL statement.
     *
     * This method is provided for use where the query object is passed to a function for modification.
     * If you have direct access to the database object, it is recommended you use the escape method directly.
     *
     * Note that 'e' is an alias for this method as it is in JEmailDriver.
     *
     * @param string  $text  The string to be escaped.
     * @param boolean $extra Optional parameter to provide extra escaping.
     *
     * @return string The escaped string.
     *
     * @since   11.1
     * @throws \RuntimeException if the internal db property is not a valid object.
     */
    public function escape($text, $extra = false);

    /**
     * Returns the primary key following insert
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    public function getInsertId($sql);

    /**
     * Returns the primary key following insert
     *
     * //$this->query->__toString()
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    public function getQueryString($sql);

    /**
     * Set the Email Query
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    public function setQuery($sql);

    /**
     * Single Value Result returned in $this->query_results
     *
     * @param   $primary_prefix
     * @param   $table_name
     *
     * @return  object
     * @since   1.0
     */
    public function loadResult();

    /**
     * Single Value Result returned in $this->query_results
     *
     * @param   $primary_prefix
     * @param   $table_name
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    public function loadObjectList();

    /**
     * Execute the Email Query
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    public function execute($sql);

    /**
     * Disconnect to Email
     *
     * @param   string  $driver
     * @param   string  $host
     * @param   string  $user
     * @param   string  $password
     * @param   string  $database_name
     * @param   string  $table_prefix
     * @param   string  $namespace
     * @param   array   $database_namespace
     *
     * @return  mixed
     * @since   1.0
     * @throws  EmailException
     */
    public function disconnect();
}
