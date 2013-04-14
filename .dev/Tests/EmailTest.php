<?php
/**
 * Email Test
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Email\Test;

defined('MOLAJO') or die;

/**
 * Email Test
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Email Object
     */
    protected $emailInstance;

    /**
     * @var Email Object
     */
    protected $Email_folder;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        $class = 'Molajo\\Email\\Adapter';

        $email_type = 'PhpMailer';
        $email_class = 'Phpmailer\\phpmailer';
        $options = array();

        $this->emailInstance = new $class($email_type, $email_class, $options);

        return;
    }

    /**
     * Create a Email entry or set a parameter value
     *
     * @covers Molajo\Email\Type\FileEmail::set
     */
    public function testSet()
    {
        $this->emailInstance->set('to', 'AmyStephen@gmail.com,Fname Lname');
        $this->emailInstance->set('from', 'AmyStephen@gmail.com,Fname Lname');
        $this->emailInstance->set('reply_to', 'AmyStephen@gmail.com,FName LName');
        $this->emailInstance->set('cc', 'AmyStephen@gmail.com,FName LName');
        $this->emailInstance->set('bcc', 'AmyStephen@gmail.com,FName LName');
        $this->emailInstance->set('subject', 'Welcome to our Site');
        $this->emailInstance->set('body', '<h2>Stuff goes here</h2>') ;
        $this->emailInstance->set('mailer_html_or_text', 'html') ;

        $this->emailInstance->send() ;

    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }
}
