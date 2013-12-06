<?php
/**
 * PhpMailer Email Class
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Handler;

use Exception;
use PHPMailer as mailer;
use CommonApi\Email\EmailInterface;
use CommonApi\Exception\RuntimeException;
use CommonApi\Exception\UnexpectedValueException;

/**
 * Edits, filters input, and sends email
 *
 * Inject via the constructor or use 'set' method, as follows:
 *
 * $adapter->set('to', 'person@example.com,Person Name');
 * $adapter->set('from', 'person@example.com,Person Name');
 * $adapter->set('reply_to', 'person@example.com,Person Name');
 * $adapter->set('cc', 'person@example.com,Person Name');
 * $adapter->set('bcc', 'person@example.com,Person Name');
 * $adapter->set('subject', 'Welcome to our Site');
 * $adapter->set('body', '<h2>Stuff goes here</h2>');
 * $adapter->set('mailer_html_or_text', 'html');
 * $adapter->set('attachment', $path_to_file);
 *
 * $adapter->send();
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
class PhpMailer extends AbstractHandler implements EmailInterface
{
    /**
     * Email Instance
     *
     * @var     object
     * @since   1.0
     */
    protected $email;

    /**
     * Send email
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function send()
    {
        if ((int)$this->mailer_disable_sending == 1) {
            return $this;
        }

        if (trim($this->mailer_only_deliver_to) == '') {
        } else {
            $filtered = $this->filterEmailAddress($this->mailer_only_deliver_to);

            $this->reply_to = $filtered;
            $this->from     = $filtered;
            $this->to       = $filtered;
            $this->cc       = '';
            $this->bcc      = '';
        }

        $this->instantiateEmail();
        $this->setSubject();
        $this->setBody();
        $this->setAttachment();
        $this->setRecipient('reply_to');
        $this->setRecipient('from');
        $this->setRecipient('to');
        $this->setRecipient('cc');
        $this->setRecipient('bcc');

        try {

            $this->email->Send();

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email PhpMailer Handler: Caught Exception: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Instantiate phpMailer Class
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function instantiateEmail()
    {
        try {

            $this->email = new mailer();

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email PhpMailer Handler: Could not instantiate phpMailer');
        }

        return $this;
    }

    /**
     * Set Subject
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setSubject()
    {
        $value = (string)$this->subject;

        if (trim($value) === '') {
            $value = $this->site_name;
        }

        $this->subject = $this->filterString($value);

        try {

            $this->email->set('Subject', $this->subject);

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email PhpMailer Handler: Exception in setSubject: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Set Body
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setBody()
    {
        if ($this->mailer_html_or_text == 'html') {

            try {
                $this->email->set('Body', $this->filterHtml($this->body));
                $this->email->IsHTML(true);

            } catch (Exception $e) {

                throw new RuntimeException
                ('Email PhpMailer Handler: Exception in setBody (HTML): ' . $e->getMessage());
            }
        }

        try {

            $this->email->set('Body', $this->filterString($this->body));

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email PhpMailer Handler: Exception in setBody (text): ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Process Email Attachment
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setAttachment()
    {
        if ($this->attachment === '' || $this->attachment === null) {
            return $this;
        }

        if (file_exists($this->attachment)) {
        } else {
            throw new RuntimeException
            ('Email Attachment File does not exist: ' . $this->attachment);
        }

        try {

            $this->email->AddAttachment(
                $this->attachment,
                $name = 'Attachment',
                $encoding = 'base64',
                $type = 'application/octet-stream'
            );

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email PhpMailer Handler: Exception in setAttachment: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Filter and send to phpMail email address and name values
     *
     * @param   string $field_name
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setRecipient($field_name)
    {
        $x = explode(';', $this->$field_name);

        if (is_array($x)) {
            $y = $x;
        } else {
            $y = array($x);
        }

        if (count($y) == 0) {
            return $this;
        }

        foreach ($y as $z) {

            $extract = explode(',', $z);
            if (count($extract) == 0) {
                break;
            }

            if ($z === false || $z == '') {
                break;
            }

            $z = $this->filterEmailAddress($extract[0]);
            if ($z === false || $z == '') {
                break;
            }
            $use_email = $z;

            $use_name = '';
            if (count($extract) > 1) {
                $z = $this->filterString($extract[0]);
                if ($z === false || $z == '') {
                } else {
                    $use_name = $z;
                }
            }

            if ($field_name == 'reply_to') {
                $method = 'AddReplyTo';

            } elseif ($field_name == 'from') {
                $method = 'setFrom';

            } elseif ($field_name == 'cc') {
                $method = 'addCC';

            } elseif ($field_name == 'bcc') {
                $method = 'addBCC';

            } else {
                $method = 'addAddress';
            }

            try {

                $this->email->$method($use_email, $use_name);

            } catch (Exception $e) {

                throw new RuntimeException
                ('Email PhpMailer Handler: Exception in setAttachment: ' . $e->getMessage());
            }
        }

        return $this;
    }

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
}
