<?php
/**
 * Email Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Email;

use Exception;
use CommonApi\Exception\RuntimeException;
use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\IoC\FactoryMethod\Base as FactoryMethodBase;
use PHPMailer;
use Swift_Message;
use Swift_MailTransport;

/**
 * Email Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class EmailFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['product_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = true;
        $options['product_namespace']        = 'Molajo\\Email\\Driver';

        parent::__construct($options);
    }

    /**
     * Instantiate a new adapter and inject it into the Adapter for the FactoryInterface     * Retrieve a list of Interface dependencies and return the data ot the controller.
     *
     * @param   array $reflection
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function setDependencies(array $reflection = array())
    {
        $this->reflection = $reflection;

        $options                           = array();
        $this->dependencies['Runtimedata'] = $options;

        return $this->dependencies;
    }

    /**
     * Set Dependency values
     *
     * @param   array $dependency_values (ignored in Service Item Adapter, based in from adapter)
     *
     * @return  $this
     * @since   1.0
     */
    public function onBeforeInstantiation(array $dependency_values = null)
    {
        parent::onBeforeInstantiation($dependency_values);

        $this->dependencies['mailer_transport']       = null;
        $this->dependencies['site_name']              = null;
        $this->dependencies['smtpauth']               = null;
        $this->dependencies['smtphost']               = null;
        $this->dependencies['smtpuser']               = null;
        $this->dependencies['smtppass']               = null;
        $this->dependencies['smtpsecure']             = null;
        $this->dependencies['smtpport']               = null;
        $this->dependencies['sendmail_path']          = null;
        $this->dependencies['mailer_disable_sending'] = null;

        $this->dependencies['mailer_transport']
            = $this->dependencies['Runtimedata']->application->parameters->mailer_transport;

        $this->dependencies['site_name']
            = $this->dependencies['Runtimedata']->application->parameters->application_name;

        switch ($this->dependencies['Runtimedata']->application->parameters->mailer_transport) {

            case 'smtp':
                $this->dependencies['smtpauth']
                    = $this->dependencies['Runtimedata']->application->parameters->mailer_smtpauth;
                $this->dependencies['smtphost']
                    = $this->dependencies['Runtimedata']->application->parameters->mailer_smtphost;
                $this->dependencies['smtpuser']
                    = $this->dependencies['Runtimedata']->application->parameters->mailer_smtpuser;
                $this->dependencies['smtppass']
                    = $this->dependencies['Runtimedata']->application->parameters->mailer_smtppass;
                $this->dependencies['smtpsecure']
                    = $this->dependencies['Runtimedata']->application->parameters->mailer_smtpsecure;
                $this->dependencies['smtpport']
                    = $this->dependencies['Runtimedata']->application->parameters->mailer_smtpport;

                break;

            case 'sendmail':
                $this->dependencies['sendmail_path']
                    = $this->dependencies['Runtimedata']->application->parameters->mailer_send_mail;

                break;

            default:
                break;
        }

        $this->dependencies['to']
            = $this->dependencies['Runtimedata']->application->parameters->mailer_only_deliver_to;
        $this->dependencies['from']
            = $this->dependencies['Runtimedata']->application->parameters->mailer_mail_from;
        $this->dependencies['reply_to']
                                             = $this->dependencies['Runtimedata']->application->parameters->mailer_mail_reply_to;
        $this->dependencies['email_adapter'] = 'phpMailer';
//= $this->dependencies['Runtimedata']->application->parameters->email_adapter;
        $this->dependencies['mailer_disable_sending']
            = $this->dependencies['Runtimedata']->application->parameters->mailer_disable_sending;
        $this->dependencies['mailer_only_deliver_to']
            = 'AmyStephen@gmail.com';

        return $dependency_values;
    }

    /**
     * Instantiate Class
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function instantiateClass()
    {
        try {
            $adapter = $this->getPhpMailerAdapter();

        } catch (Exception $e) {

            throw new RuntimeException
            (
                'Email Factory Method Adapter Instance Failed for ' . $this->product_namespace
                . ' failed.' . $e->getMessage()
            );
        }

        $class = $this->product_namespace;

        try {
            $this->product_result = new $class($adapter);
        } catch (Exception $e) {

            throw new RuntimeException
            (
                'Email: Could not instantiate Adapter for Email Type: ' . $adapter
            );
        }

//$this->testEmail();

        return $this;
    }

    /**
     * Get PhpMailer Email Adapter
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getPhpMailerAdapter()
    {
        $class = 'Molajo\\Email\\Adapter\\PhpMailer';

        try {
            return new $class(
                $this->instantiatePHPMailer(),
                $this->dependencies['mailer_transport'],
                $this->dependencies['site_name'],
                $this->dependencies['smtpauth'],
                $this->dependencies['smtphost'],
                $this->dependencies['smtpuser'],
                $this->dependencies['smtppass'],
                $this->dependencies['smtpsecure'],
                $this->dependencies['smtpport'],
                $this->dependencies['sendmail_path'],
                $this->dependencies['mailer_disable_sending']
            );
        } catch (Exception $e) {

            throw new RuntimeException
            (
                'Email: Could not instantiate Email Adapter Adapter: ' . $class
            );
        }
    }

    /**
     * Instantiate phpMailer Class
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function getSwiftMailerAdapter()
    {
        try {
            $this->message = Swift_Message::newInstance();

        } catch (Exception $e) {

            throw new RuntimeException
            (
                'Email Swiftmailer Adapter: Could not instantiate phpMailer'
            );
        }

        try {
            $this->mailer = Swift_MailTransport::newInstance();

        } catch (Exception $e) {

            throw new RuntimeException
            (
                'Email Swiftmailer Adapter: Could not instantiate phpMailer'
            );
        }

        return $this;
    }

    /**
     * Instantiate phpMailer Class
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    protected function instantiatePHPMailer()
    {
        try {
            return new PHPMailer();

        } catch (Exception $e) {

            throw new RuntimeException
            (
                'Email PhpMailer Adapter: Could not instantiate phpMailer'
            );
        }
    }

    /**
     *  Test after connection
     */
    protected function testEmail()
    {
        /** Test */
        $this->product_result->set('to', 'AmyStephen@Molajo.org,Amy Stephen');
        $this->product_result->set('from', 'AmyStephen@Molajo.org,Amy Stephen');
        $this->product_result->set('reply_to', 'person@example.com,FName LName');
        $this->product_result->set('cc', 'person@example.com,FName LName');
        $this->product_result->set('bcc', 'person@example.com,FName LName');
        $this->product_result->set('subject', 'Welcome to our Site');
        $this->product_result->set('body', '<h2>Stuff goes here</h2>');
        $this->product_result->set('mailer_html_or_text', 'html');

        $this->product_result->send();
    }
}
