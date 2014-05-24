<?php
/**
 * PhpMailer Email Class
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Adapter;

use CommonApi\Email\EmailInterface;
use CommonApi\Exception\RuntimeException;
use Exception;

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

        $this->setOnlyDeliverTo();

        $this->setSubject();
        $this->setBody();
        $this->setAttachment();

        $this->addEmailByType('from', '');
        $this->addEmailByType('reply_to', 'addReplyTo');
        $this->addEmailByType('to', 'addAddress');
        $this->addEmailByType('cc', 'addcc');
        $this->addEmailByType('bcc', 'addbcc');

        $this->sendMail();

        return true;
    }

    /**
     * Only Deliver To
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function setOnlyDeliverTo()
    {
        if ($this->mailer_only_deliver_to === '') {
            return $this;
        }

        $filtered = $this->filterEmailAddress($this->mailer_only_deliver_to);

        $this->reply_to = $filtered;
        $this->from     = $filtered;
        $this->to       = $filtered;
        $this->cc       = '';
        $this->bcc      = '';

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
        $filtered = $this->filterString($this->subject);

        if (trim($filtered) === '') {
            $filtered = $this->filterString($this->site_name);
        }

        $this->email_instance->Subject = $filtered;

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

        $filtered = $this->filterBody();

        $this->email_instance->Body    = $filtered;

        if ($this->mailer_html_or_text === 'html') {
            $this->email_instance->AltBody = (string)$filtered;
        }

        return $this;
    }

    /**
     * Filter Body
     *
     * @return  string
     * @since   1.0.0
     */
    protected function filterBody()
    {
        if ($this->mailer_html_or_text == 'html') {
            $filtered = $this->filterHtml($this->body);
            $this->email_instance->isHTML(true);
        } else {
            $filtered = $this->filterString($this->body);
            $this->email_instance->isHTML(false);
        }

        if (strlen($filtered) < 5) {
            throw new RuntimeException(
                'Email PhpMailer Adapter: No message body for email.'
            );
        }

        return $filtered;
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
        if (file_exists($this->attachment)) {
        } else {
            return $this;
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
     * Add email address to phpMailer
     *
     * @param   string $type
     * @param   string $method
     * @param   string $type
     *
     * @return  $this
     * @since   1.0
     */
    protected function addEmailByType($type, $method)
    {
        $list = $this->setRecipient($this->$type);

        if (count($list) > 0) {
            foreach ($list as $item) {
                $this->addEmailByTypeItem($type, $method, $item);
            }
        }
    }

    /**
     * Add Email By Type -- single Item
     *
     * @param   string $type
     * @param   string $method
     * @param   object $item
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function addEmailByTypeItem($type, $method, $item)
    {
        try {
            if ($type === 'from') {
                $this->email_instance->From     = $item->email;
                $this->email_instance->FromName = $item->name;
            } else {
                $this->email_instance->$method($item->email, $item->name);
            }

        } catch (Exception $e) {
            throw new RuntimeException(
                'Email PhpMailer Adapter: Exception in phpMailer: ' . $method . ' ' . $e->getMessage()
            );
        }
    }

    /**
     * All fields processed, send email
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function sendMail()
    {
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
    }
}
