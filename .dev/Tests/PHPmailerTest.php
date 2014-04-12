<?php
/**
 * Email Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Email\Test;

use stdClass;

/**
 * Email Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class PHPmailerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Email
     */
    protected $email;

    /**
     * @var Email Instance
     */
    protected $email_instance;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        $this->email_instance   = new PHPMailer();
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
        $cc                     = 'AmyStephen@gmail.com,Amy Stephen';
        $bcc                    = 'AmyStephen@gmail.com';
        $subject                = 'Test phpEmail';
        $body                   = '<p>Message in here.</p>';
        $mailer_html_or_text    = 'text';
        $attachment             = '';

        $class = 'Molajo\\Email\\Adapter\\PhpMailer';

        $adapter = new $class(
            $this->email_instance,
            $mailer_transport,
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

        $class       = 'Molajo\\Email\\Driver';
        $this->email = new $class($adapter);

        return;
    }

    /**
     * Create a Email entry or set a parameter value
     *
     * @covers Molajo\Email\Adapter\FileEmail::set
     */
    public function testDisableEmail()
    {
        $this->email->set('mailer_disable_sending', true);
        $results = $this->email->get('mailer_disable_sending');
        $this->assertEquals(true, $results);
    }

    /**
     * Create a Email entry or set a parameter value
     *
     * @covers Molajo\Email\Adapter\FileEmail::set
     */
    public function testOnlyDeliverTo()
    {
        $this->email->set('mailer_only_deliver_to', 'dog@example.com');
        $results = $this->email->get('mailer_only_deliver_to');
        $this->assertEquals('dog@example.com', $results);
    }

    /**
     * Test all parameters sent in for an HTML email
     *
     * @covers Molajo\Email\Adapter\FileEmail::set
     */
    public function testSendHtml()
    {
        $file = __DIR__ . '/PHPmailerTest.php';

        $this->email->set('to', 'person@example.com,Person Name');
        $this->email->set('from', 'person@example.com,Person Name');
        $this->email->set('reply_to', 'person@example.com,Person Name');
        $this->email->set('cc', 'person@example.com,Person Name');
        $this->email->set('bcc', 'person@example.com,Person Name');
        $this->email->set('subject', 'Welcome to our Site');
        $this->email->set('body', '<h2>Stuff goes here</h2>');
        $this->email->set('mailer_html_or_text', 'html');
        $this->email->set('attachment', $file);

        $this->email->send();

        $results = $this->email->get('email_instance');

        $this->assertEquals($results->to, array('person@example.com' => 'Person Name'));
        $this->assertEquals($results->From, 'person@example.com');
        $this->assertEquals($results->FromName, 'Person Name');
        $this->assertEquals($results->reply_to, array('person@example.com' => 'Person Name'));
        $this->assertEquals($results->cc, array('person@example.com' => 'Person Name'));
        $this->assertEquals($results->bcc, array('person@example.com' => 'Person Name'));
        $this->assertEquals($results->Subject, 'Welcome to our Site');
        $this->assertEquals($results->Body, '&lt;h2&gt;Stuff goes here&lt;/h2&gt;');
        $this->assertEquals($results->mailer_html_or_text, true);
        $this->assertEquals($results->attachment, $file);
    }

    /**
     * Test all parameters sent in for an HTML email
     *
     * @covers Molajo\Email\Adapter\FileEmail::set
     */
    public function testSendText()
    {
        $file = __DIR__ . '/PHPmailerTest.php';

        $this->email->set('to', 'person@example.com,Person Name');
        $this->email->set('from', 'person@example.com,Person Name');
        $this->email->set('reply_to', 'person@example.com,Person Name');
        $this->email->set('cc', 'person@example.com,Person Name');
        $this->email->set('bcc', 'person@example.com,Person Name');
        $this->email->set('subject', 'Welcome to our Site');
        $this->email->set('body', '<h2>Stuff goes here</h2>');
        $this->email->set('mailer_html_or_text', 'text');
        $this->email->set('attachment', $file);

        $this->email->send();

        $results = $this->email->get('email_instance');

        $this->assertEquals($results->to, array('person@example.com' => 'Person Name'));
        $this->assertEquals($results->From, 'person@example.com');
        $this->assertEquals($results->FromName, 'Person Name');
        $this->assertEquals($results->reply_to, array('person@example.com' => 'Person Name'));
        $this->assertEquals($results->cc, array('person@example.com' => 'Person Name'));
        $this->assertEquals($results->bcc, array('person@example.com' => 'Person Name'));
        $this->assertEquals($results->Subject, 'Welcome to our Site');
        $this->assertEquals($results->Body, 'Stuff goes here');
        $this->assertEquals($results->mailer_html_or_text, false);
        $this->assertEquals($results->attachment, $file);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
}

/**
 * PHPMailer Mock
 */
class PHPMailer
{
    public $mailer_transport;

    public $smtpauth;
    public $smtphost;
    public $smtpuser;
    public $smtppass;
    public $smtpsecure;
    public $smtpport;

    public $sendmail_path = 0;

    public $mailer_disable_sending = 0;
    public $mailer_only_deliver_to = '';

    public $to = array();
    public $reply_to = array();
    public $cc = array();
    public $bcc = array();

    public $Subject = '';
    public $From = '';
    public $FromName = '';
    public $Body = '';
    public $AltBody = '';
    public $WordWrap = '';
    public $mailer_html_or_text = '';
    public $attachment = '';

    /**
     * List of Properties
     *
     * @var    object
     * @since  1.0
     */
    public $property_array = array(
        'mailer_transport',
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
        'Subject',
        'From',
        'FromName',
        'Body',
        'AltBody',
        'WordWrap',
        'mailer_html_or_text',
        'attachment'
    );

    public function isHTML($indicator)
    {
        $this->mailer_html_or_text = $indicator;
    }

    public function addAttachment($attachment)
    {
        $this->attachment = $attachment;
    }

    public function addReplyTo($email, $name)
    {
        $this->reply_to[$email] = $name;
    }

    public function addAddress($email, $name)
    {
        $this->to[$email] = $name;
    }

    public function addCC($email, $name)
    {
        $this->cc[$email] = $name;
    }

    public function addBCC($email, $name)
    {
        $this->bcc[$email] = $name;
    }

    public function send()
    {
    }

    public function get()
    {
        $row = new stdClass();
        foreach ($this->property_array as $item) {
            $row->$item = $this->$item;
        }

        return $row;
    }
}
