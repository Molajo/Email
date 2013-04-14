<?php
/**
 * Email
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
define('MOLAJO', 'This is a Molajo Distribution');

if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $base);

//include BASE_FOLDER . '/Tests/Testcase1/Data.php';

$classMap = array(
    'Molajo\\Email\\Exception\\EmailException'     => BASE_FOLDER . '/Exception/EmailException.php',
    'Molajo\\Email\\Exception\\ExceptionInterface' => BASE_FOLDER . '/Exception/ExceptionInterface.php',
    'Molajo\\Email\\Api\\EmailInterface'       => BASE_FOLDER . '/Api/EmailInterface.php',
    'Molajo\\Email\\Type\\AbstractType'            => BASE_FOLDER . '/Type/AbstractType.php',
    'Molajo\\Email\\Type\\PhpMailer'           => BASE_FOLDER . '/Type/PhpMailer.php',
    'Molajo\\Email\\Adapter'                       => BASE_FOLDER . '/Adapter.php',

    'PhpMailer\\phpmailer' => '/Users/amystephen/Sites/Standard/Vendor/PhpMailer/phpmailer.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
