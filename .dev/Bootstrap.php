<?php
/**
 * Bootstrap for Testing
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
include_once __DIR__ . '/CreateClassMap.php';

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', phpversion());
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

$base     = substr(__DIR__, 0, strlen(__DIR__) - 5);
$classmap = array();

$results = createClassMap($base . '/Handler', 'Molajo\\Email\\Handler\\');
$classmap = array_merge($classmap, $results);
$results = createClassMap($base . '/Service/Email', 'Molajo\\Service\\Email\\');
$classmap = array_merge($classmap, $results);
$results = createClassMap($base . '/vendor/commonapi/email', 'CommonApi\\Email\\');
$classmap = array_merge($classmap, $results);
$results = createClassMap($base . '/vendor/commonapi/exception', 'CommonApi\\Exception\\');
$classmap = array_merge($classmap, $results);

$classmap['Molajo\\Email\\Adapter']   = $base . '/Adapter.php';
ksort($classmap);

spl_autoload_register(
    function ($class) use ($classmap) {
        if (array_key_exists($class, $classmap)) {
            require_once $classmap[$class];
        }
    }
);

$phpmailer_autoloader = $base . '/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
if (file_exists($phpmailer_autoloader)) {
    include $phpmailer_autoloader;
}

$swift_required = $base . '/vendor/swiftmailer/swiftmailer/lib/swift_required.php';
if (file_exists($swift_required)) {
    include $swift_required;
}
