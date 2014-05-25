<?php
/**
 * Abstract Email Class
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Adapter;

use CommonApi\Email\EmailInterface;
use CommonApi\Exception\RuntimeException;
use CommonApi\Exception\UnexpectedValueException;
use stdClass;

/**
 * Adapter for Email
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
abstract class AbstractAdapter implements EmailInterface
{
    /**
     * Email Instance
     *
     * @var     object
     * @since   1.0
     */
    protected $email_instance;

    /**
     * Mailer Transport - smtp, sendmail, ismail
     *
     * @var     string
     * @since   1.0
     */
    protected $mailer_transport;

    /**
     * Site Name
     *
     * @var     string
     * @since   1.0
     */
    protected $site_name;

    /**
     * SMTP Authorisation
     *
     * @var     string
     * @since   1.0
     */
    protected $smtpauth;

    /**
     * SMTP Host
     *
     * @var     string
     * @since   1.0
     */
    protected $smtphost;

    /**
     * SMTP User
     *
     * @var     string
     * @since   1.0
     */
    protected $smtpuser;

    /**
     * SMTP Password
     *
     * @var     string
     * @since   1.0
     */
    protected $smtppass;

    /**
     * SMTP Secure
     *
     * @var     string
     * @since   1.0
     */
    protected $smtpsecure;

    /**
     * SMTP Port
     *
     * @var     string
     * @since   1.0
     */
    protected $smtpport;

    /**
     * Sendmail Path
     *
     * @var     bool
     * @since   1.0
     */
    protected $sendmail_path = 0;

    /**
     * Disable Sending
     *
     * @var     bool
     * @since   1.0
     */
    protected $mailer_disable_sending = 0;

    /**
     * Only Deliver To
     *
     * @var     string
     * @since   1.0
     */
    protected $mailer_only_deliver_to = '';

    /**
     * To
     *
     * @var     array
     * @since   1.0
     */
    protected $to = array();

    /**
     * From
     *
     * @var     array
     * @since   1.0
     */
    protected $from = array();

    /**
     * Reply To
     *
     * @var     array
     * @since   1.0
     */
    protected $reply_to = array();

    /**
     * Copy
     *
     * @var     array
     * @since   1.0
     */
    protected $cc = array();

    /**
     * Blind Copy
     *
     * @var     array
     * @since   1.0
     */
    protected $bcc = array();

    /**
     * Subject
     *
     * @var     string
     * @since   1.0
     */
    protected $subject = '';

    /**
     * Body
     *
     * @var     string
     * @since   1.0
     */
    protected $body = '';

    /**
     * HTML or Text
     *
     * @var     string
     * @since   1.0
     */
    protected $mailer_html_or_text = '';

    /**
     * Attachment
     *
     * @var     string
     * @since   1.0
     */
    protected $attachment = '';

    /**
     * List of Properties
     *
     * @var    array
     * @since  1.0
     */
    protected $property_array
        = array(
            'email_instance',
            'mailer_only_deliver_to',
            'to',
            'from',
            'reply_to',
            'cc',
            'bcc',
            'subject',
            'body',
            'mailer_html_or_text',
            'attachment',
            'mailer_disable_sending'
        );

    /**
     * Construct
     *
     * @param   object $email_instance
     * @param   string $mailer_transport
     * @param   string $site_name
     * @param   string $smtpauth
     * @param   string $smtphost
     * @param   string $smtpuser
     * @param   string $smtppass
     * @param   string $smtpsecure
     * @param   string $smtpport
     * @param   string $sendmail_path
     *
     * @since   1.0
     */
    public function __construct(
        $email_instance,
        $mailer_transport = '',
        $site_name = '',
        $smtpauth = '',
        $smtphost = '',
        $smtpuser = '',
        $smtppass = '',
        $smtpsecure = '',
        $smtpport = '',
        $sendmail_path = ''
    ) {
        $this->email_instance         = $email_instance;
        $this->mailer_transport       = $mailer_transport;
        $this->site_name              = $site_name;
        $this->smtpauth               = $smtpauth;
        $this->smtphost               = $smtphost;
        $this->smtpuser               = $smtpuser;
        $this->smtppass               = $smtppass;
        $this->smtpsecure             = $smtpsecure;
        $this->smtpport               = $smtpport;
        $this->sendmail_path          = $sendmail_path;
    }

    /**
     * Set parameter value
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function set($key, $value = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new RuntimeException('Email: attempting to set value for unknown property: ' . $key);
        }

        $this->$key = $value;

        return $this;
    }

    /**
     * Return value for a key
     *
     * @param   null|string $key
     * @param   mixed       $default
     *
     * @return  mixed
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function get($key, $default = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new RuntimeException('Email: attempting to get value for unknown property: ' . $key);
        }

        if ($this->$key === null) {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Send email
     *
     * @return  mixed
     * @since   1.0
     */
    abstract public function send();

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     */
    abstract public function close();

    /**
     * Filter and send to email address and name values
     *
     * @param   string $list
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setRecipient($list)
    {
        $return_results = array();

        $items = explode(';', $list);

        if (count($items) > 0) {
        } else {
            return $return_results;
        }

        foreach ($items as $item) {
            if ($item === '') {
            } else {
                $return_results = $this->setRecipientItem($item, $return_results);
            }
        }

        return $return_results;
    }

    /**
     * Extract an email address and name
     *
     * @param   string $item
     * @param   array $return_results
     *
     * @return  $return_results
     * @since   1.0
     */
    protected function setRecipientItem($item, $return_results)
    {
        $return_item = $this->extractSingleEmailName($item);

        if ($return_item === false) {
        } else {
            $return_results[] = $return_item;
        }

        return $return_results;
    }

    /**
     * Extract an email address and name
     *
     * @param   string $item
     *
     * @return  stdClass
     * @since   1.0
     */
    protected function extractSingleEmailName($item)
    {
        $split = explode(',', $item);

        if (count($split) === 0) {
            return false;
        }

        $return_item        = new stdClass();
        $return_item->email = $this->filterEmailAddress($split[0]);

        if (count($split) > 1) {
            $return_item->name = $this->filterString($split[1]);
        } else {
            $return_item->name = '';
        }

        return $return_item;
    }

    /**
     * Validate Email Address
     *
     * @param   string $email_address
     *
     * @return  string
     * @since   1.0
     * @throws  \CommonApi\Exception\UnexpectedValueException
     */
    protected function filterEmailAddress($email_address)
    {
        if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            return $email_address;
        }

        throw new UnexpectedValueException('Email Filter Email Address Failed');
    }

    /**
     * Filter String
     *
     * @param   string $string
     *
     * @return  string
     * @since   1.0
     * @throws  \CommonApi\Exception\UnexpectedValueException
     */
    protected function filterString($string)
    {
        return strip_tags($string, '<p><a><b><i>');
    }

    /**
     * Filter Html
     *
     * @param   string $html
     *
     * @return  string
     * @since   1.0
     * @throws  \CommonApi\Exception\UnexpectedValueException
     */
    protected function filterHtml($html)
    {
        $filtered = htmlentities($html, ENT_QUOTES, 'UTF-8');

        if ($filtered === false) {
            throw new UnexpectedValueException('Email Filter HTML Failed');
        }

        return $filtered;
    }
}
