<?php
/**
 * PhpMailer Email Class
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Adapter;

use Exception;
use CommonApi\Email\EmailInterface;
use CommonApi\Exception\RuntimeException;

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
class PhpMailer extends AbstractAdapter implements EmailInterface
{
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

        $this->setSubject();
        $this->setBody();
        $this->setAttachment();
        $this->setReplyTo();
        $this->from();
        $this->setTo();
        $this->setCC();
        $this->setBCC();

        try {

            $response = $this->email_instance->send();

            if ($response === false) {
                throw new RuntimeException(
                    'Email PhpMailer Adapter failed in send Method. Error: ' . $this->email_instance->ErrorInfo
                );
            }

        } catch (Exception $e) {

            throw new RuntimeException(
                'Email PhpMailer Adapter: Caught Exception: ' . $e->getMessage()
            );
        }

        return true;
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
            $this->email_instance->Subject = $this->subject;

        } catch (Exception $e) {

            throw new RuntimeException(
                'Email PhpMailer Adapter: Exception in setSubject: ' . $e->getMessage()
            );
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
        $this->email_instance->WordWrap = 50;

        try {

            if ($this->mailer_html_or_text == 'html') {
                $results = $this->filterHtml($this->body);
            } else {
                $results = $this->filterString($this->body);
            }

            if ($results === false || trim($results) === '') {
                throw new RuntimeException(
                    'Email PhpMailer Adapter: No message body (HTML) sent in for email'
                );
            }

            if ($this->mailer_html_or_text == 'html') {
                $this->email_instance->isHTML(true);
                $this->email_instance->Body    = $results;
                $this->email_instance->AltBody = (string)$results;
            } else {
                $this->email_instance->isHTML(false);
                $this->email_instance->Body = $results;
            }

        } catch (Exception $e) {

            throw new RuntimeException(
                'Email PhpMailer Adapter: Exception in setBody (HTML): ' . $e->getMessage()
            );
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
            throw new RuntimeException(
                'Email Attachment File does not exist: ' . $this->attachment
            );
        }

        try {
            $this->email_instance->addAttachment($this->attachment);

        } catch (Exception $e) {

            throw new RuntimeException(
                'Email PhpMailer Adapter: Exception in setAttachment: ' . $e->getMessage()
            );
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
                $results = $this->email_instance->addReplyTo($item->email, $item->name);

            } catch (Exception $e) {

                throw new RuntimeException(
                    'Email PhpMailer Adapter: Exception in setAttachment: ' . $e->getMessage()
                );
            }

            if ($results === false) {
                throw new RuntimeException(
                    'Email PhpMailer Adapter: False return from phpMailer addReplyTo'
                );
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
    protected function from()
    {
        $list = $this->setRecipient($this->from);

        if (is_array($list)) {
        } else {
            return $this;
        }

        foreach ($list as $item) {

            try {
                $this->email_instance->From     = $item->email;
                $this->email_instance->FromName = $item->name;

            } catch (Exception $e) {

                throw new RuntimeException(
                    'Email PhpMailer Adapter: Exception in setRecipient setTo: ' . $e->getMessage()
                );
            }

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

        foreach ($list as $item) {

            try {
                $results = $this->email_instance->addAddress($item->email, $item->name);

            } catch (Exception $e) {

                throw new RuntimeException(
                    'Email PhpMailer Adapter: Exception in setRecipient setTo: ' . $e->getMessage()
                );
            }

            if ($results === false) {
                throw new RuntimeException(
                    'Email PhpMailer Adapter: False return from phpMailer setTo'
                );
            }
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
        $this->setCopyRecipient('cc');

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
        $this->setCopyRecipient('bcc');

        return $this;
    }

    /**
     * Set BCC Recipient
     *
     * @param   string  $type
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setCopyRecipient($type)
    {
        $list = $this->setRecipient($this->$type);

        if (is_array($list)) {
        } else {
            return $this;
        }

        foreach ($list as $item) {

            try {
                $model = 'add' . $type;
                $results = $this->email_instance->$model($item->email, $item->name);

            } catch (Exception $e) {

                throw new RuntimeException(
                    'Email PhpMailer Adapter: Exception in setCopyRecipient ' . $type . ': ' . $e->getMessage()
                );
            }

            if ($results === false) {
                // OK when the bcc is in the other lists
            }
        }

        return $this;
    }
}
