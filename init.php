<?php
/** note: this can also be called for cli scripts.
 * note: parse errors in this file would cause a Fatal Parse error
 * with message display (unless display_errors is set Off in php.ini),
 * using ini_set() will not turn off the display

 */
define('APP_ROOT', __DIR__ . '/' );

require APP_ROOT . 'vendor/autoload.php';
require APP_ROOT . 'utilities/functions.php';

// in case of collision, env.php value overrides
$config = array_merge(require APP_ROOT . 'config/config.php', require APP_ROOT . 'config/env.php');

$errorHandler = new \It_All\BoutiqueCommerce\Utilities\ErrorHandler($config['errors']['reportMethods'], $config['logs']['pathPhpErrors'], $config['env']);
register_shutdown_function(array($errorHandler, 'checkForFatal'));
set_exception_handler(array($errorHandler, 'throwableHandler'));

error_reporting( -1 ); // all, including future types
ini_set( 'display_errors', 'off' );
ini_set( 'display_startup_errors', 'off' );
// keep this even though the error handler logs errors, any errors in the error handler itself or prior to will still be logged
ini_set('error_log', $config['logs']['pathPhpErrors']);


//use It_All\BoutiqueCommerce\Utilities as Utilities;
//
//$m = It_All\BoutiqueCommerce\Utilities\Mailer::getPhpmailer($config['phpmailer']['protocol'], $config['phpmailer']['smtpHost'], $config['phpmailer']['smtpPort']);
//
//$m->Subject = 'test';
//$m->Body = 'test';
//$m->addAddress('greg@it-all.com');
//$m->send();

//if (!Utilities::isRunningFromCommandLine()) {
//    /**
//     * verify/force all pages to be https. and verify/force www or not www based on Config::useWww
//     * if not, REDIRECT TO PROPER SECURE PAGE
//     * note this practice is ok:
//     * http://security.stackexchange.com/questions/49645/actually-isnt-it-bad-to-redirect-http-to-https
//     */
//    if (!Utilities::isHttps() || (Config::$domainUseWWW && !Utilities::isWww()) || (!Config::$domainUseWWW && Utilities::isWww())) {
//        Utilities::redirect();
//    }
//
//    /** prevent XSS */
//    Utilities::arrayProtectRecursive($_POST);
//    Utilities::arrayProtectRecursive($_GET);
//
//    /** SESSION */
//    $sessionTTLseconds = Config::$sessionTTLhours * 60 * 60;
//    ini_set('session.gc_maxlifetime', $sessionTTLseconds);
//    ini_set('session.cookie_lifetime', $sessionTTLseconds);
//    if (!Utilities::sessionValidId(session_id())) {
//        session_regenerate_id(true);
//    }
//    session_start();
//    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
//
//}
