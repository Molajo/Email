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
 * Edits, filters input, and sends email
 *
 * Example usage:
 *
 * Services::Email()->set('to', 'person@example.com,Fname Lname');
 * Services::Email()->set('from', 'person@example.com,Fname Lname');
 * Services::Email()->set('reply_to', 'person@example.com,FName LName');
 * Services::Email()->set('cc', 'person@example.com,FName LName');
 * Services::Email()->set('bcc', 'person@example.com,FName LName');
 * Services::Email()->set('subject', 'Welcome to our Site');
 * Services::Email()->set('body', '<h2>Stuff goes here</h2>') ;
 * Services::Email()->set('mailer_html_or_text', 'html') ;
 * Services::Email()->set('attachment', SITE_MEDIA_FOLDER.'/molajo.sql') ;
 *
 * Services::Email()->send();
 *
 * @package     Molajo
 * @subpackage  Service
 * @since       1.0
 */
class PhpMailer extends AbstractType implements EmailInterface
{

    /**
     * Construct
     *
     * @param   string $email_class
     * @param   array  $options
     *
     * @return  object  EmailInterface
     * @since   1.0
     * @throws  EmailException
     */
    public function __construct($email_class, $options = array())
    {
        parent::__construct($email_class, $options);

        $this->setEmailPackage($email_class, $options);
    }

    /**
     * Set the Email Type (PhpMailer, Swiftmailer)
     *
     * @param   string $email_type
     *
     * @return  $this
     * @since   1.0
     * @throws  EmailException
     */
    public function setEmailPackage($email_class, $options = array())
    {
        $class = 'PhpMailer\\phpmailer';

        if (class_exists($class)) {
        } else {
            throw new EmailException
            ('Email Type PhpMailer Class: ' . $class . ' does not exist.');
        }

        $this->mailInstance = new $class($email_class);

        return $this;
    }

    /**
     * Send email
     *
     * @return  $this
     * @since   1.0
     * @throws  EmailException
     */
    public function send()
    {
        if ($this->get('mailer_disable_sending', 0) == 1) {
            return $this;
        }

        if (trim($this->get('mailer_only_deliver_to', '') == '')) {
        } else {
            $this->set('reply_to', $this->mailer_only_deliver_to);
            $this->set('from', $this->mailer_only_deliver_to);
            $this->set('to', $this->mailer_only_deliver_to);
            $this->set('cc', '');
            $this->set('bcc', '');
        }

        $this->error_count = 0;

        $this->setSubject();

        $this->processRecipient('reply_to');
        $this->processRecipient('from');
        $this->processRecipient('to');
        $this->processRecipient('cc');
        $this->processRecipient('bcc');

        if ($this->get('mailer_html_or_text', 'text') == 'html') {
            $mailer_html_or_text = 'text';
        } else {
            $mailer_html_or_text = 'char';
        }
        if ($mailer_html_or_text == 'html') {
            $this->mailInstance->IsHTML(true);
        }

        $body     = $this->get('body');
        $filtered = $this->filterInput('body', $body, $mailer_html_or_text);
        $this->mailInstance->set('Body', $filtered);

        $attachment = $this->get('attachment', '');
        if ($attachment == '') {
        } else {
            $attachment = $this->filterInput('attachment', $attachment, 'file');
        }
        if ($attachment === false || $attachment == '') {
        } else {
            $this->mailInstance->AddAttachment(
                $attachment,
                $name = 'Attachment',
                $encoding = 'base64',
                $type = 'application/octet-stream'
            );
        }

        switch ($this->get('mailer_transport')) {

            case 'smtp':
                $this->mailInstance->mailer_smtpauth        = $this->get('mailer_smtpauth');
                $this->mailInstance->smtphost               = $this->get('smtphost');
                $this->mailInstance->mailer_smtpuser        = $this->get('mailer_smtpuser');
                $this->mailInstance->mailer_mailer_smtphost = $this->get('mailer_mailer_smtphost');
                $this->mailInstance->smtpsecure             = $this->get('smtpsecure');
                $this->mailInstance->smtpport               = $this->get('smtpport');

                $this->mailInstance->IsSMTP();

                break;

            case 'sendmail':
                $this->mailInstance->mailer_smtpauth        = $this->get('sendmail_path');

                $this->mailInstance->IsSendmail();
                break;

            default:
                $this->mailInstance->IsMail();
                break;
        }

        return $this;
    }

    /**
     * permission
     *
     * Verify user and extension have permission to send email
     *
     * @return bool
     * @since   1.0
     */
    protected function permission()
    {
        $permission = true;

        /** Resource (authorises any user) */

        /** User */

        /** authorization event */

        //@todo what is the catalog id of a service?
        //$results = Services::Permissions()->verifyTask('email', $catalog_id);
        return $permission;
    }

    /**
     * Set Subject
     *
     * @return  object
     * @since   1.0
     * @throws  EmailException
     */
    public function setSubject()
    {
        $value = (string)$this->get('subject', '');

        if ($value == '') {
            $value = $this->site_name;
        }

        $value = $this->filterInput('subject', $value, 'char');

        $this->mailInstance->set('Subject', $value);

        return $this;
    }

    /**
     * Filter and send to phpMail email address and name values
     *
     * @param   string $field_name
     *
     * @return  null
     * @since   1.0
     */
    protected function processRecipient($field_name)
    {
        $x = explode(';', $this->get($field_name));

        if (is_array($x)) {
            $y = $x;
        } else {
            $y = array($x);
        }

        if (count($y) == 0) {
            return;
        }

        foreach ($y as $z) {

            $extract = explode(',', $z);
            if (count($extract) == 0) {
                break;
            }

            if ($z === false || $z == '') {
                break;
            }
            $z = $this->filterInput($field_name, $extract[0], 'email');
            if ($z === false || $z == '') {
                break;
            }
            $useEmail = $z;

            $useName = '';
            if (count($extract) > 1) {
                $z = $this->filterInput($field_name, $extract[1], 'char');
                if ($z === false || $z == '') {
                } else {
                    $useName = $z;
                }
            }

            if ($field_name == 'reply_to') {
                $this->mailInstance->AddReplyTo($useEmail, $useName);

            } elseif ($field_name == 'from') {
                $this->mailInstance->SetFrom($useEmail, $useName);

            } elseif ($field_name == 'cc') {
                $this->mailInstance->AddCC($useEmail, $useName);

            } elseif ($field_name == 'bcc') {
                $this->mailInstance->AddBCC($useEmail, $useName);

            } else {
                $this->mailInstance->AddAddress($useEmail, $useName);
            }
        }
    }

    /**
     * filterInput
     *
     * @param string $name     Name of input field
     * @param string $value    Value of input field
     * @param string $dataType Datatype of input field
     * @param int    $null     0 or 1 - is null allowed
     * @param string $default  Default value, optional
     *
     * @return mixed
     * @since   1.0
     */
    protected function filterInput(
        $name,
        $value,
        $dataType,
        $null = null,
        $default = null
    ) {


        return $value;
    }
}
