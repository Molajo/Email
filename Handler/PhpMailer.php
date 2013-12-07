<?php
/**
 * PhpMailer Email Class
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Email\Handler;

use stdClass;
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
        $this->setReplyTo();
        $this->setFrom();
        $this->setTo();
        $this->setCC();
        $this->setBCC();

        try {

            $response = $this->email->send();

            if ($response === false) {
                throw new RuntimeException
                ('Email PhpMailer Handler failed in send Method. Error: ' . $this->email->ErrorInfo);
            }

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

            $this->email->Subject = $this->subject;

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
        $this->email->WordWrap = 50;

        if ($this->mailer_html_or_text == 'html') {

            try {

                $results = $this->filterHtml($this->body);
                if ($results === false || trim($results) === '') {
                    throw new RuntimeException
                    ('Email PhpMailer Handler: No message body (HTML) sent in for email');
                }

                $this->email->isHTML(true);
                $this->email->Body    = $results;
                $this->email->AltBody = (string)$results;

            } catch (Exception $e) {

                throw new RuntimeException
                ('Email PhpMailer Handler: Exception in setBody (HTML): ' . $e->getMessage());
            }
        }


        try {
            $results = $this->filterString($this->body);

            if ($results === false || trim($results) === '') {
                throw new RuntimeException
                ('Email PhpMailer Handler: No message body sent in for email');
            }

            $this->email->isHTML(false);
            $this->email->Body = $results;

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
            $results = $this->email->addAttachment($this->attachment);

        } catch (Exception $e) {

            throw new RuntimeException
            ('Email PhpMailer Handler: Exception in setAttachment: ' . $e->getMessage());
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
                $results = $this->email->addReplyTo($item->email, $item->name);

            } catch (Exception $e) {

                throw new RuntimeException
                ('Email PhpMailer Handler: Exception in setAttachment: ' . $e->getMessage());
            }

            if ($results === false) {
                throw new RuntimeException
                ('Email PhpMailer Handler: False return from phpMailer addReplyTo');
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

        foreach ($list as $item) {

            try {
                $this->email->From     = $item->email;
                $this->email->FromName = $item->name;

            } catch (Exception $e) {

                throw new RuntimeException
                ('Email PhpMailer Handler: Exception in setRecipient setTo: ' . $e->getMessage());
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
                $results = $this->email->addAddress($item->email, $item->name);

            } catch (Exception $e) {

                throw new RuntimeException
                ('Email PhpMailer Handler: Exception in setRecipient setTo: ' . $e->getMessage());
            }

            if ($results === false) {
                throw new RuntimeException
                ('Email PhpMailer Handler: False return from phpMailer setTo');
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
        $list = $this->setRecipient($this->cc);

        if (is_array($list) && count($list) > 0) {
        } else {
            return $this;
        }

        foreach ($list as $item) {

            try {
                $results = $this->email->addCC($item->email, $item->name);

            } catch (Exception $e) {

                throw new RuntimeException
                ('Email PhpMailer Handler: Exception in setRecipient CC: ' . $e->getMessage());
            }

            if ($results === false) {
                throw new RuntimeException
                ('Email PhpMailer Handler: False return from phpMailer addCC');
            }
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
        $list = $this->setRecipient($this->bcc);

        if (is_array($list)) {
        } else {
            return $this;
        }

        foreach ($list as $item) {

            try {
                $results = $this->email->addBCC($item->email, $item->name);

            } catch (Exception $e) {

                throw new RuntimeException
                ('Email PhpMailer Handler: Exception in setRecipient BCC: ' . $e->getMessage());
            }

            if ($results === false) {
                throw new RuntimeException
                ('Email PhpMailer Handler: False return from phpMailer addBCC');
            }
        }

        return $this;
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
     * Get Email Address
     *
     * @return  $this
     * @since   1.0
     */
    public function extractEmailAddress($email)
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
    public function extractName($name)
    {
        $results = $this->filterEmailAddress($name);

        if ($results === false || trim($name) === '') {
            return false;
        }

        return $name;
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
