<?php
/** note this can also be called for cli scripts */

$rpp = explode('/', __DIR__);
define('APP_ROOT', implode('/', $rpp).'/' );

require APP_ROOT . 'vendor/autoload.php';
require APP_ROOT . 'utilities/errorHandlers.php';
require APP_ROOT . 'utilities/functions.php';

// in case of collision, config.php value overrides
$config = array_merge(require APP_ROOT . 'config/env.php', require APP_ROOT . 'config/config.php');

//use It_All\BoutiqueCommerce\Utilities as Utilities;

$m = It_All\BoutiqueCommerce\Utilities\Mailer::getPhpmailer($config['phpmailer']['protocol'], $config['phpmailer']['smtpHost'], $config['phpmailer']['smtpPort']);

$m->Subject = 'test';
$m->Body = 'test';
$m->addAddress('greg@it-all.com');
$m->send();

/**
 * error handling
 * using the shutdown function is a workaround to have fatal errors handled
 */
register_shutdown_function('It_All\BoutiqueCommerce\Utilities\checkForFatal');
set_error_handler('It_All\BoutiqueCommerce\Utilities\errorHandler');
set_exception_handler('It_All\BoutiqueCommerce\Utilities\exceptionHandler');

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
