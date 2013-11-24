<?php
/**
 * Email
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    MIT
 */

if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $base);

//include BASE_FOLDER . '/Tests/Testcase1/Data.php';

$classMap = array(
    'Molajo\\Email\\CommonApi\\EmailAwareInterface'    => BASE_FOLDER . '/Api/EmailAwareInterface.php',
    'Molajo\\Email\\CommonApi\\EmailInterface'         => BASE_FOLDER . '/Api/EmailInterface.php',
    'Molajo\\Email\\CommonApi\\ExceptionInterface'     => BASE_FOLDER . '/Api/ExceptionInterface.php',
    'Exception\\Email\\AbstractHandlerException' => BASE_FOLDER . '/Exception/AbstractHandlerException.php',
    'Exception\\Email\\AdapterException'         => BASE_FOLDER . '/Exception/AdapterException.php',
    'Exception\\Email\\ConnectionException'      => BASE_FOLDER . '/Exception/ConnectionException.php',
    'Exception\\Email\\DummyHandlerException'    => BASE_FOLDER . '/Exception/DummyHandlerException.php',
    'Exception\\Email\\EmailException'           => BASE_FOLDER . '/Exception/EmailException.php',
    'Molajo\\Email\\Handler\\AbstractHandler'    => BASE_FOLDER . '/Handler/AbstractHandler.php',
    'Molajo\\Email\\Handler\\PhpMailer'          => BASE_FOLDER . '/Handler/PhpMailer.php',
    'Molajo\\Email\\Adapter'                     => BASE_FOLDER . '/Adapter.php',
    'PhpMailer\\PhpMailer'
                                                 => '/Users/amystephen/Sites/Standard/Vendor/PhpMailer/PhpMailer.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
