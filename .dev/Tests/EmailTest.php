<?php
/**
 * Email Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Email\Test;

/**
 * Email Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Email Object
     */
    protected $email;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        $mailer_transport       = "mail";
        $site_name              = 'Test Site';
        $smtpauth               = '';
        $smtphost               = '';
        $smtpuser               = '';
        $smtppass               = '';
        $smtpsecure             = '';
        $smtpport               = '';
        $sendmail_path          = '';
        $mailer_disable_sending = 0;
        $mailer_only_deliver_to = '';
        $to                     = 'AmyStephen@gmail.com';
        $from                   = 'AmyStephen@gmail.com';
        $reply_to               = 'AmyStephen@gmail.com,Amy Stephen';
        $cc                     = 'AmyStephen@molajo.com,Amy Stephen';
        $bcc                    = 'AmyStephen@example.com';
        $subject                = 'Test phpEmail';
        $body                   = '<p>Message in here.</p>';
        $mailer_html_or_text    = 'text';
        $attachment             = '';

        $class       = 'Molajo\\Email\\Handler\\PhpMailer';
        $handler     = new $class($mailer_transport,
            $site_name,
            $smtpauth,
            $smtphost,
            $smtpuser,
            $smtppass,
            $smtpsecure,
            $smtpport,
            $sendmail_path,
            $mailer_disable_sending,
            $mailer_only_deliver_to,
            $to,
            $from,
            $reply_to,
            $cc,
            $bcc,
            $subject,
            $body,
            $mailer_html_or_text,
            $attachment
        );
        $class       = 'Molajo\\Email\\Adapter';
        $this->email = new $class($handler);

        return;
    }

    /**
     * Create a Email entry or set a parameter value
     *
     * @covers Molajo\Email\Handler\FileEmail::set
     */
    public function testSet()
    {
        $this->email->send();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
}
