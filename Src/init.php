<?php
declare(strict_types=1);

/** note: this can also be called for cli scripts.*/
define('APP_ROOT', __DIR__ . '/' );

require APP_ROOT . '/../vendor/autoload.php';
require APP_ROOT . 'Infrastructure/Utilities/functions.php';

use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities;

// in case of collision, env.php value overrides
$config = array_merge(
    require APP_ROOT . 'config/config.php',
    require APP_ROOT . 'config/env.php'
);

// instantiate mailer, which is used in error handler and container
$mailer = new \It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\PhpMailerService(
    $config['storage']['logs']['pathPhpErrors'],
    $config['emails']['service'],
    $config['businessName'],
    $config['phpmailer']['protocol'],
    $config['phpmailer']['smtpHost'],
    $config['phpmailer']['smtpPort']
);

// error handling
$emailErrorsTo = [];
foreach ($config['errors']['emailTo'] as $roleEmail) {
    $emailErrorsTo[] = $config['emails'][$roleEmail];
}
$errorHandler = new Utilities\ErrorHandler(
    $config['storage']['logs']['pathPhpErrors'],
    $config['hostName']."/",
    $config['isLive'],
    $config['errors']['emailDev'],
    $mailer,
    $emailErrorsTo
);

// workaround for catching some fatal errors like parse errors. note that parse errors in this file and index.php are not handled, but cause a fatal error with display (not displayed if display_errors is off in php.ini, but the ini_set call will not affect it).
register_shutdown_function(array($errorHandler, 'checkForFatal'));
set_error_handler(array($errorHandler, 'phpErrorHandler'));
set_exception_handler(array($errorHandler, 'throwableHandler'));

error_reporting( -1 ); // all, including future types
ini_set( 'display_errors', 'off' );
ini_set( 'display_startup_errors', 'off' );

// keep this even though the error handler logs errors, so that any errors in the error handler itself or prior to will still be logged. note, if using slim error handling, this will log all php errors
ini_set('error_log', $config['storage']['logs']['pathPhpErrors']);

if (!Utilities\isRunningFromCommandLine()) {
    /**
     * verify/force all pages to be https. and verify/force www or not www based on Config::useWww
     * if not, REDIRECT TO PROPER SECURE PAGE
     * note this practice is ok:
     * http://security.stackexchange.com/questions/49645/actually-isnt-it-bad-to-redirect-http-to-https
     */
    if (!Utilities\isHttps() || ($config['domainUseWww'] && !Utilities\isWww()) || (!$config['domainUseWww'] && Utilities\isWww())) {
        Utilities\redirect();
    }

    /** SESSION */
    $sessionTTLseconds = $config['session']['ttlHours'] * 60 * 60;
    ini_set('session.gc_maxlifetime', (string) $sessionTTLseconds);
    ini_set('session.cookie_lifetime', (string) $sessionTTLseconds);
    if (!Utilities\sessionValidId(session_id())) {
        session_regenerate_id(true);
    }
    session_save_path($config['session']['savePath']);
    session_start();
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
}
