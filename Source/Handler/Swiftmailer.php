<?php
/**
 * Swiftmailer Email Class
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Handler;

use Exception;
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
class Swiftmailer extends AbstractHandler implements EmailInterface
{
    /**
     * Message Instance
     *
     * @var     object
     * @since   1.0
     */
    protected $message;

    /**
     * Mailer Instance
     *
     * @var     object
     * @since   1.0
     */
    protected $mailer;

    /**
     * Send email
     *
     * @return  bool
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function send()
    {
        if ((int)$this->mailer_disable_sending == 1) {
            return false;
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
        $this->setReplyTo();
        $this->setFrom();
        $this->setTo();
        $this->setCC();
        $this->setBCC();

        try {

            $response = $this->mailer->send($this->message);

            if ($response === false) {
                throw new RuntimeException
                ('Email Swiftmailer Handler failed in send Method. Error: ' . $this->message->ErrorInfo);
            }

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Caught Exception: ' . $e->getMessage());
        }

        return true;
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
            $this->message = \Swift_Message::newInstance();

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Could not instantiate phpMailer');
        }

        try {
            $this->mailer = \Swift_MailTransport::newInstance();

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Could not instantiate phpMailer');
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
            $this->message->setSubject($this->subject);

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Exception in setSubject: ' . $e->getMessage());
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
        $this->message->WordWrap = 50;

        try {

            if ($this->mailer_html_or_text == 'html') {
                $results = $this->filterHtml($this->body);
            } else {
                $results = $this->filterString($this->body);
            }

            if ($results === false || trim($results) === '') {
                throw new RuntimeException
                ('Email Swiftmailer Handler: No message body (HTML) sent in for email');
            }

            if ($this->mailer_html_or_text == 'html') {
                $this->message->setBody($results);
                $this->message->addPart((string)$results);
            } else {
                $this->message->setBody((string)$results);
            }

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Exception in setBody (HTML): ' . $e->getMessage());
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
            $this->message->attach(Swift_Attachment::fromPath($this->attachment));

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Exception in setAttachment: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Set Reply To Recipient
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setReplyTo()
    {
        $list = $this->setRecipient($this->reply_to);

        if (is_array($list) && count($list) > 0) {
        } else {
            return $this;
        }

        foreach ($list as $item) {

            try {
                $results = $this->message->setReturnPath($item->email);

            } catch (Exception $e) {

                throw new RuntimeException
                ('Email Swiftmailer Handler: Exception in setAttachment: ' . $e->getMessage());
            }

            if ($results === false) {
                throw new RuntimeException
                ('Email Swiftmailer Handler: False return from phpMailer addReplyTo');
            }
        }

        return $this;
    }

    /**
     * Set From Recipient
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setFrom()
    {
        $list = $this->setRecipient($this->from);

        if (is_array($list)) {
        } else {
            return $this;
        }

        $from = array();
        foreach ($list as $item) {
            $from[$item->email] = $item->name;
        }

        try {
            $this->message->setFrom($from);

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Exception in setRecipient setTo: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Set To Recipient
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setTo()
    {
        $list = $this->setRecipient($this->to);

        if (is_array($list)) {
        } else {
            return $this;
        }

        $to = array();
        foreach ($list as $item) {
            $to[$item->email] = $item->name;
        }

        try {
            $this->message->setTo($to);

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Exception in setRecipient setTo: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Set CC Recipient
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setCC()
    {
        return $this;
        $list = $this->setRecipient($this->cc);

        return $this;

        if (is_array($list) && count($list) > 0) {
        } else {
            return $this;
        }

        $cc = array();
        foreach ($list as $item) {
            $cc[$item->email] = $item->name;
        }

        try {
            $this->message->addCc($cc);

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Exception in setRecipient cc: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Set BCC Recipient
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setBCC()
    {
        return $this;
        $list = $this->setRecipient($this->bcc);

        if (is_array($list)) {
        } else {
            return $this;
        }

        $bcc = array();
        foreach ($list as $item) {
            $bcc[$item->email] = $item->name;
        }

        try {
            $this->message->addBcc($bcc);

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email Swiftmailer Handler: Exception in setRecipient Bcc: ' . $e->getMessage());
        }

        return $this;
    }
}
