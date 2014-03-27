<?php
/**
 * Abstract Email Class
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Adapter;

use stdClass;
use CommonApi\Email\EmailInterface;
use CommonApi\Exception\RuntimeException;
use CommonApi\Exception\UnexpectedValueException;

/**
 * Adapter for Email
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
abstract class AbstractAdapter implements EmailInterface
{
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
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtpauth;

    /**
     * SMTP Host
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtphost;

    /**
     * SMTP User
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtpuser;

    /**
     * SMTP Password
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtppass;

    /**
     * SMTP Secure
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $smtpsecure;

    /**
     * SMTP Port
     *
     * @var     EmailInterface
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
     * @var    object
     * @since  1.0
     */
    protected $property_array = array(
        'mailer_transport',
        'site_name',
        'smtpauth',
        'smtphost',
        'smtpuser',
        'smtppass',
        'smtpsecure',
        'smtpport',
        'sendmail_path',
        'mailer_disable_sending',
        'mailer_only_deliver_to',
        'to',
        'from',
        'reply_to',
        'cc',
        'bcc',
        'subject',
        'body',
        'mailer_html_or_text',
        'attachment'
    );

    /**
     * Construct
     *
     * @param   string $mailer_transport
     * @param   string $site_name
     * @param   string $smtpauth
     * @param   string $smtphost
     * @param   string $smtpuser
     * @param   string $smtppass
     * @param   string $smtpsecure
     * @param   string $smtpport
     * @param   string $sendmail_path
     * @param   string $mailer_disable_sending
     * @param   string $mailer_only_deliver_to
     * @param   string $to
     * @param   string $from
     * @param   string $reply_to
     * @param   string $cc
     * @param   string $bcc
     * @param   string $subject
     * @param   string $body
     * @param   string $mailer_html_or_text
     * @param   string $attachment
     *
     * @since   1.0
     */
    public function __construct(
        $mailer_transport = '',
        $site_name = '',
        $smtpauth = '',
        $smtphost = '',
        $smtpuser = '',
        $smtppass = '',
        $smtpsecure = '',
        $smtpport = '',
        $sendmail_path = '',
        $mailer_disable_sending = '',
        $mailer_only_deliver_to = '',
        $to = '',
        $from = '',
        $reply_to = '',
        $cc = '',
        $bcc = '',
        $subject = '',
        $body = '',
        $mailer_html_or_text = '',
        $attachment = ''
    ) {
        $this->mailer_transport       = $mailer_transport;
        $this->site_name              = $site_name;
        $this->smtpauth               = $smtpauth;
        $this->smtphost               = $smtphost;
        $this->smtpuser               = $smtpuser;
        $this->smtppass               = $smtppass;
        $this->smtpsecure             = $smtpsecure;
        $this->smtpport               = $smtpport;
        $this->sendmail_path          = $sendmail_path;
        $this->mailer_disable_sending = $mailer_disable_sending;
        $this->mailer_only_deliver_to = $mailer_only_deliver_to;
        $this->to                     = $to;
        $this->from                   = $from;
        $this->reply_to               = $reply_to;
        $this->cc                     = $cc;
        $this->bcc                    = $bcc;
        $this->subject                = $subject;
        $this->body                   = $body;
        $this->mailer_html_or_text    = $mailer_html_or_text;
        $this->attachment             = $attachment;
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
            throw new RuntimeException
            ('Email: attempting to set value for unknown property: ' . $key);
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
            throw new RuntimeException
            ('Email: attempting to get value for unknown property: ' . $key);
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
    public function close()
    {
        return $this;
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
        $filtered = (string)$string;

        if ($filtered === false) {
            throw new UnexpectedValueException ('Email Filter String Failed for: ' . $string);
        }

        return $filtered;
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
        $filtered = filter_input($html, FILTER_SANITIZE_SPECIAL_CHARS);

        if ($filtered === false) {
            throw new UnexpectedValueException ('Email Filter HTML Failed for: ' . $html);
        }

        return $filtered;
    }

    /**
     * Filter and send to phpMail email address and name values
     *
     * @param   string $list
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setRecipient($list)
    {
        $items = explode(';', $list);

        if (count($items) > 0 && is_array($items)) {
        } else {
            return false;
        }

        $return_results = array();

        foreach ($items as $split_this) {

            $split = explode(',', $split_this);

            if (is_array($split) && count($split) > 0) {
            } else {
                break;
            }

            $return_item = new stdClass();

            $x = $this->extractEmailAddress($split[0]);

            if ($x === false) {
            } else {
                $return_item->email = $x;
                $return_item->name  = '';

                if (isset($split[1])) {
                    $x = $this->extractName($split[1]);
                    if ($x === false) {
                    } else {
                        $return_item->name = $x;
                    }
                }

                $return_results[] = $return_item;
            }
        }

        return $return_results;
    }

    /**
     * Filter Email Address
     *
     * @param   string $email_address
     *
     * @return  string
     * @since   1.0
     * @throws  \CommonApi\Exception\UnexpectedValueException
     */
    protected function filterEmailAddress($email_address)
    {
        $filtered = filter_var($this->mailer_only_deliver_to, FILTER_SANITIZE_EMAIL);

        if ($filtered === false) {
            throw new UnexpectedValueException ('Email Filter Email Address Failed for: ' . $email_address);
        }

        return $filtered;
    }

    /**
     * Get Email Address
     *
     * @return  $this
     * @since   1.0
     */
    protected function extractEmailAddress($email)
    {
        $results = $this->filterEmailAddress($email);

        if ($results === false || trim($email) === '') {
            return false;
        }

        return $email;
    }

    /**
     * Get Email Address
     *
     * @return  $this
     * @since   1.0
     */
    protected function extractName($name)
    {
        $results = (string)$name;

        if ($results === false || trim($name) === '') {
            return false;
        }

        return $name;
    }
}
