<?php
/**
 * Abstract Email Class
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Type;

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
class AbstractType implements EmailInterface
{
    /**
     * Email Instance and Configuration
     */

    /**
     * Mail Instance
     *
     * @var     string
     * @since   1.0
     */
    protected $mailInstance;

    /**
     * Site Name
     *
     * @var     string
     * @since   1.0
     */
    protected $site_name;

    /**
     * Email Instance and Configuration
     */

    /**
     * Email Instance
     *
     * @var     EmailInterface
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
     * SMTP
     */

    /**
     * SMTP Authorisation
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $mailer_smtpauth;

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
    protected $mailer_smtpuser;

    /**
     * SMTP Host
     *
     * @var     EmailInterface
     * @since   1.0
     */
    protected $mailer_mailer_smtphost;

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
     * Sendmail
     */

    /**
     * Sendmail Path
     *
     * @var     bool
     * @since   1.0
     */
    protected $sendmail_path = 0;

    /**
     * Message
     */

    /**
     * Sender
     *
     * @var     string
     * @since   1.0
     */
    protected $sender = '';

    /**
     * Recipient
     *
     * @var     array
     * @since   1.0
     */
    protected $recipient = array();

    /**
     * Reply To
     *
     * @var     array
     * @since   1.0
     */
    protected $reply_to = array();

    /**
     * From
     *
     * @var     array
     * @since   1.0
     */
    protected $from = array();

    /**
     * To
     *
     * @var     array
     * @since   1.0
     */
    protected $to = array();

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
     * Testing Support
     */

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
        'options',
        'email_instance',
        'mailer_transport',
        'mailer_smtpauth',
        'mailer_smtpuser',
        'mailer_mailer_smtphost',
        'smtpsecure',
        'smtpport',
        'sender',
        'recipient',
        'reply_to',
        'from',
        'to',
        'cc',
        'bcc',
        'subject',
        'body',
        'mailer_html_or_text',
        'mailer_disable_sending',
        'mailer_only_deliver_to',
        'attachment'
    );

    /**
     * Construct
     *
     * @param   string  $email_class
     * @param   array   $options
     *
     * @return  object  EmailInterface
     * @since   1.0
     * @throws  EmailException
     */
    public function __construct($email_class = 'phpmailer\\PHPMailer', $options = array())
    {
        $this->email_class = $email_class;
        $this->options = $options;
    }

    /**
     * Get the Email Type (PhpMailer, Swiftmailer)
     *
     * @param   string $email_type
     *
     * @return  $this
     * @since   1.0
     * @throws  EmailException
     */
    public function setEmailPackage($email_class, $options = array())
    {
        if (class_exists($email_class)) {
        } else {
            throw new EmailException
            ('Email Package Class ' . $email_class . ' does not exist.');
        }

        $this->email_instance = new $email_class();

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
     * @throws  EmailException
     */
    public function get($key, $default = null)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new EmailException
            ('Email Service: attempting to get value for unknown property: ' . $key);
        }

        if ($this->key === null) {
            $this->$key = $default;
        }

        return $this->$key;
    }

    /**
     * Set value for a key
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    public function set($key, $value)
    {
        $key = strtolower($key);

        if (in_array($key, $this->property_array)) {
        } else {
            throw new EmailException
            ('Email Service: attempting to set value for unknown key: ' . $key);
        }

        $this->$key = $value;

        return $this->$key;
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
        return $this;
    }
}
