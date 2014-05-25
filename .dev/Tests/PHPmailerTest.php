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
     * @covers Molajo\Email\Driver::__construct
     * @covers Molajo\Email\Driver::get
     * @covers Molajo\Email\Driver::set
     * @covers Molajo\Email\Driver::send
     *
     * @covers Molajo\Email\Adapter\PhpMailer::__construct
     * @covers Molajo\Email\Adapter\PhpMailer::send
     * @covers Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers Molajo\Email\Adapter\PhpMailer::setBody
     * @covers Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers Molajo\Email\Adapter\AbstractAdapter::set
     * @covers Molajo\Email\Adapter\AbstractAdapter::get
     * @covers Molajo\Email\Adapter\AbstractAdapter::send
     * @covers Molajo\Email\Adapter\AbstractAdapter::close
     * @covers Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterHtml
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
            $mailer_disable_sending
        );

        $class       = 'Molajo\\Email\\Driver';
        $this->email = new $class($adapter);

        return;
    }

    /**
     * Create a Email entry or set a parameter value
     *
     * @covers Molajo\Email\Driver::__construct
     * @covers Molajo\Email\Driver::get
     * @covers Molajo\Email\Driver::set
     * @covers Molajo\Email\Driver::send
     *
     * @covers Molajo\Email\Adapter\PhpMailer::__construct
     * @covers Molajo\Email\Adapter\PhpMailer::send
     * @covers Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers Molajo\Email\Adapter\PhpMailer::setBody
     * @covers Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers Molajo\Email\Adapter\AbstractAdapter::set
     * @covers Molajo\Email\Adapter\AbstractAdapter::get
     * @covers Molajo\Email\Adapter\AbstractAdapter::send
     * @covers Molajo\Email\Adapter\AbstractAdapter::close
     * @covers Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterHtml
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
     * @covers Molajo\Email\Driver::__construct
     * @covers Molajo\Email\Driver::get
     * @covers Molajo\Email\Driver::set
     * @covers Molajo\Email\Driver::send
     *
     * @covers Molajo\Email\Adapter\PhpMailer::__construct
     * @covers Molajo\Email\Adapter\PhpMailer::send
     * @covers Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers Molajo\Email\Adapter\PhpMailer::setBody
     * @covers Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers Molajo\Email\Adapter\AbstractAdapter::set
     * @covers Molajo\Email\Adapter\AbstractAdapter::get
     * @covers Molajo\Email\Adapter\AbstractAdapter::send
     * @covers Molajo\Email\Adapter\AbstractAdapter::close
     * @covers Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterHtml
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
     * @covers Molajo\Email\Driver::__construct
     * @covers Molajo\Email\Driver::get
     * @covers Molajo\Email\Driver::set
     * @covers Molajo\Email\Driver::send
     *
     * @covers Molajo\Email\Adapter\PhpMailer::__construct
     * @covers Molajo\Email\Adapter\PhpMailer::send
     * @covers Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers Molajo\Email\Adapter\PhpMailer::setBody
     * @covers Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers Molajo\Email\Adapter\AbstractAdapter::set
     * @covers Molajo\Email\Adapter\AbstractAdapter::get
     * @covers Molajo\Email\Adapter\AbstractAdapter::send
     * @covers Molajo\Email\Adapter\AbstractAdapter::close
     * @covers Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterHtml
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
     * @covers Molajo\Email\Driver::__construct
     * @covers Molajo\Email\Driver::get
     * @covers Molajo\Email\Driver::set
     * @covers Molajo\Email\Driver::send
     *
     * @covers Molajo\Email\Adapter\PhpMailer::__construct
     * @covers Molajo\Email\Adapter\PhpMailer::send
     * @covers Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers Molajo\Email\Adapter\PhpMailer::setBody
     * @covers Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers Molajo\Email\Adapter\AbstractAdapter::set
     * @covers Molajo\Email\Adapter\AbstractAdapter::get
     * @covers Molajo\Email\Adapter\AbstractAdapter::send
     * @covers Molajo\Email\Adapter\AbstractAdapter::close
     * @covers Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterHtml
     */
    public function testSendText()
    {
        $file = __DIR__ . '/PHPmailerTest.php';

        $this->email->set('mailer_only_deliver_to', '');
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
     * Test all parameters sent in for an HTML email
     *
     * @covers Molajo\Email\Driver::__construct
     * @covers Molajo\Email\Driver::get
     * @covers Molajo\Email\Driver::set
     * @covers Molajo\Email\Driver::send
     *
     * @covers Molajo\Email\Adapter\PhpMailer::__construct
     * @covers Molajo\Email\Adapter\PhpMailer::send
     * @covers Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers Molajo\Email\Adapter\PhpMailer::setBody
     * @covers Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers Molajo\Email\Adapter\AbstractAdapter::set
     * @covers Molajo\Email\Adapter\AbstractAdapter::get
     * @covers Molajo\Email\Adapter\AbstractAdapter::send
     * @covers Molajo\Email\Adapter\AbstractAdapter::close
     * @covers Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterHtml
     */
    public function testDisableSending()
    {
        $file = __DIR__ . '/PHPmailerTest.php';

        $this->email->set('mailer_disable_sending', 1);
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

        $this->assertEquals($results->to, array());
        $this->assertEquals($results->From, '');
    }

    /**
     * Test all parameters sent in for an HTML email
     *
     * @covers Molajo\Email\Driver::__construct
     * @covers Molajo\Email\Driver::get
     * @covers Molajo\Email\Driver::set
     * @covers Molajo\Email\Driver::send
     *
     * @covers Molajo\Email\Adapter\PhpMailer::__construct
     * @covers Molajo\Email\Adapter\PhpMailer::send
     * @covers Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers Molajo\Email\Adapter\PhpMailer::setBody
     * @covers Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers Molajo\Email\Adapter\AbstractAdapter::set
     * @covers Molajo\Email\Adapter\AbstractAdapter::get
     * @covers Molajo\Email\Adapter\AbstractAdapter::send
     * @covers Molajo\Email\Adapter\AbstractAdapter::close
     * @covers Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterHtml
     */
    public function testOnlyDeliverToAndSend()
    {
        $file = __DIR__ . '/PHPmailerTest.php';

        $this->email->set('mailer_only_deliver_to', 'AmyStephen@gmail.com');
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

        $this->assertEquals($results->to, array('AmyStephen@gmail.com' => ''));
        $this->assertEquals($results->From, 'AmyStephen@gmail.com');
        $this->assertEquals($results->FromName, '');
        $this->assertEquals($results->reply_to, array('AmyStephen@gmail.com' => ''));
        $this->assertEquals($results->cc, array());
        $this->assertEquals($results->bcc, array());
        $this->assertEquals($results->Subject, 'Welcome to our Site');
        $this->assertEquals($results->Body, 'Stuff goes here');
        $this->assertEquals($results->mailer_html_or_text, false);
        $this->assertEquals($results->attachment, $file);
    }

    /**
     * Test all parameters sent in for an HTML email
     *
     * @covers Molajo\Email\Driver::__construct
     * @covers Molajo\Email\Driver::get
     * @covers Molajo\Email\Driver::set
     * @covers Molajo\Email\Driver::send
     *
     * @covers Molajo\Email\Adapter\PhpMailer::__construct
     * @covers Molajo\Email\Adapter\PhpMailer::send
     * @covers Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers Molajo\Email\Adapter\PhpMailer::setBody
     * @covers Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers Molajo\Email\Adapter\AbstractAdapter::set
     * @covers Molajo\Email\Adapter\AbstractAdapter::get
     * @covers Molajo\Email\Adapter\AbstractAdapter::send
     * @covers Molajo\Email\Adapter\AbstractAdapter::close
     * @covers Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers Molajo\Email\Adapter\AbstractAdapter::filterHtml
     */
    public function testNoSubject()
    {
        $file = __DIR__ . '/test.txt';

        $this->email->set('mailer_only_deliver_to', '');
        $this->email->set('to', 'person@example.com,Person Name');
        $this->email->set('from', 'person@example.com,Person Name');
        $this->email->set('reply_to', 'person@example.com,Person Name');
        $this->email->set('cc', 'person@example.com,Person Name');
        $this->email->set('bcc', 'person@example.com,Person Name');
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
        $this->assertEquals($results->Subject, 'Test Site');
        $this->assertEquals($results->Body, 'Stuff goes here');
        $this->assertEquals($results->mailer_html_or_text, false);
        $this->assertEquals($results->attachment, $file);
    }

    /**
     * Test all parameters sent in for an HTML email
     *
     * @covers                   Molajo\Email\Driver::__construct
     * @covers                   Molajo\Email\Driver::get
     * @covers                   Molajo\Email\Driver::set
     * @covers                   Molajo\Email\Driver::send
     *
     * @covers                   Molajo\Email\Adapter\PhpMailer::__construct
     * @covers                   Molajo\Email\Adapter\PhpMailer::send
     * @covers                   Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers                   Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers                   Molajo\Email\Adapter\PhpMailer::setBody
     * @covers                   Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers                   Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers                   Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers                   Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers                   Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::set
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::get
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::send
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::close
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::filterHtml
     *
     * @expectedException \CommonApi\Exception\RuntimeException
     * @expectedExceptionMessage Email: attempting to set value for unknown property: nope
     */
    public function testInvalidSet()
    {
        $this->email->set('nope', '');
    }

    /**
     * Test all parameters sent in for an HTML email
     *
     * @covers                   Molajo\Email\Driver::__construct
     * @covers                   Molajo\Email\Driver::get
     * @covers                   Molajo\Email\Driver::set
     * @covers                   Molajo\Email\Driver::send
     *
     * @covers                   Molajo\Email\Adapter\PhpMailer::__construct
     * @covers                   Molajo\Email\Adapter\PhpMailer::send
     * @covers                   Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers                   Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers                   Molajo\Email\Adapter\PhpMailer::setBody
     * @covers                   Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers                   Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers                   Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers                   Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers                   Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::set
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::get
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::send
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::close
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::filterHtml
     *
     * @expectedException \CommonApi\Exception\RuntimeException
     * @expectedExceptionMessage Email: attempting to get value for unknown property: nope
     */
    public function testInvalidGet()
    {
        $this->email->get('nope', '');
    }

    /**
     * Test all parameters sent in for an HTML email
     *
     * @covers                   Molajo\Email\Driver::__construct
     * @covers                   Molajo\Email\Driver::get
     * @covers                   Molajo\Email\Driver::set
     * @covers                   Molajo\Email\Driver::send
     *
     * @covers                   Molajo\Email\Adapter\PhpMailer::__construct
     * @covers                   Molajo\Email\Adapter\PhpMailer::send
     * @covers                   Molajo\Email\Adapter\PhpMailer::setOnlyDeliverTo
     * @covers                   Molajo\Email\Adapter\PhpMailer::setSubject
     * @covers                   Molajo\Email\Adapter\PhpMailer::setBody
     * @covers                   Molajo\Email\Adapter\PhpMailer::filterBody
     * @covers                   Molajo\Email\Adapter\PhpMailer::setAttachment
     * @covers                   Molajo\Email\Adapter\PhpMailer::addEmailByType
     * @covers                   Molajo\Email\Adapter\PhpMailer::addEmailByTypeItem
     * @covers                   Molajo\Email\Adapter\PhpMailer::sendMail
     *
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::__construct
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::set
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::get
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::send
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::close
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::setRecipient
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::extractSingleEmailName
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::filterEmailAddress
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::filterString
     * @covers                   Molajo\Email\Adapter\AbstractAdapter::filterHtml
     *
     * @expectedException \CommonApi\Exception\UnexpectedValueException
     * @expectedExceptionMessage Email Filter Email Address Failed
     */
    public function testInvalidEmailAddress()
    {
        $file = __DIR__ . '/test.txt';
        $this->email->set('mailer_only_deliver_to', '');
        $this->email->set('to', 'dogfood,Person Name');
        $this->email->set('from', 'person@example.com,Person Name');
        $this->email->set('reply_to', 'person@example.com,Person Name');
        $this->email->set('cc', 'person@example.com,Person Name');
        $this->email->set('bcc', 'person@example.com,Person Name');
        $this->email->set('body', '<h2>Stuff goes here</h2>');
        $this->email->set('mailer_html_or_text', 'text');
        $this->email->set('attachment', $file);

        $this->email->send();
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
    public $property_array
        = array(
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
