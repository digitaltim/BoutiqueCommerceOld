<?php
namespace It_All\BoutiqueCommerce\Utilities;

//use GregCatalano\Spaghettify\Classes\Infrastructure\Utilities;
//use GregCatalano\Spaghettify\Classes\Infrastructure\Mailer;

/**
 * A function to register as the global PHP error handler.
 * @param $errno
 * @param $errstr
 * @param $errfile
 * @param $errline
 * @param optional $vars
 * NOTE, calling trigger_error('msg' E_ERROR) results in a warning 'invalid error type'. use E_USER_ERROR instead
 */
function errorHandler($errno, $errstr, $errfile, $errline, $vars = null)
{
    global $config;

    echo 'errorHandler';

    // This happens when an expression is prefixed with @ (meaning: ignore errors).
    if (error_reporting() == 0 || $config['errors']['report'] === false) {
        return;
    }

    $onErr = $config['errors']['reportMethods'];
    $bt = debug_backtrace();
    // Remove line for this function
    array_shift($bt);
    $btStr = backtraceToString($bt);

    switch ($errno) {
        case E_ERROR:
        case E_USER_ERROR:
            $errorTypeStr = 'Error';
            $subjectEnd = "Fatal PHP Error";
            $exitPage = true;
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $errorTypeStr = 'Warning';
            $subjectEnd = "PHP Warning";
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $errorTypeStr = "Notice";
            $subjectEnd = "PHP Notice";
            break;
        case E_DEPRECATED:
        case E_USER_DEPRECATED:
            $errorTypeStr = "Deprecated";
            $subjectEnd = "Deprecated";
            break;
        default:
            $errorTypeStr = "Unknown error type";
            $subjectEnd = "Unknown PHP Error";
            $exitPage = false; // die only for E_ERROR and E_USER_ERROR http://php.net/manual/en/errorfunc.constants.php or if not on live server
            break;
    }

    $serverName = isRunningFromCommandLine() ? gethostname() : $_SERVER['SERVER_NAME'];
    $subject = "$serverName $subjectEnd";

    $topBody = "$errorTypeStr: [$errno] $errstr\r\n";
    $body = $topBody . "Line $errline in file $errfile";
    $body .= "\r\n\r\n";
    if (isRunningFromCommandLine()) {
        global $argv;
        $body .= "Command line: " . $argv[0];
    } else {
        $body .= "Web Page: " . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
    }
    if (strlen($btStr) > 0) {
        $body .= "\r\n\r\nStack Trace:\r\n" . $btStr;
    }

//    // EMAIL.
//    // force email if trying to echo on live server instead of echoing
//    // if email fails mail error is added to body for echo/log
//    if (in_array('email', $onErr) || (Utilities::isLiveServer() && in_array('echo', $onErr))) {
//        $phpmailer = Mailer::getPhpmailer();
//        $phpmailer->Subject = $subject;
//        foreach (Config::$techEmails as $email) {
//            $phpmailer->addAddress($email);
//        }
//        $phpmailer->isHTML(false);
//        $phpmailer->Body = "See error log for details.";
//        if (!$phpmailer->send()) {
//            $body .= "\r\n\r\nEmail Send Failure: ";
//            $body .= $phpmailer->ErrorInfo;
//        }
//    }

    // set of errors for which to generate a var trace (TODO: WHY ONLY THESE?)
    $userErrors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
    // var trace is never to be emailed; it can be echoed on dev servers, and logged on dev and live serviers
    if (in_array($errno, $userErrors) && is_array($vars) && count($vars) > 0) {
        $body .= "\r\n\r\nVartrace";
        $body .= arrayWalkToStringRecursive($vars);
    }

    // ECHO on dev servers or from command line. suppress echoing on live server. note echoing may be suppressed by .htaccess files
    if (in_array('echo', $onErr)) {
        if (isRunningFromCommandLine()) {
            echo $subject . "\n";
            echo $body . "\n";
        } else if (!isLiveServer()) {
            $errorBlock = "<div class='adminErrorEcho'>";
            $errorBlock .= "$subject<br><pre>$body</pre>";
            $errorBlock .= "</div>";
            if (isset($ui) && !$exitPage) {
                // if exitPage is true script will die prior to ui output
                $ui->addErrorNotice($errorBlock);
            } else {
                echo $errorBlock;
            }
        }
    }

    // LOG. force logging on live server
    if (in_array('log', $onErr) || isLiveServer()) {
        error_log("\n" . date('Y-m-d H:i:s') . "\n$subject:\n$body", 3, APP_ROOT . $config['logs']['pathPhpErrors']);
    }

    if ($exitPage) {
        exit(1);
    }
}

/**
 * Uncaught exception handler.
 * see comment by Philip http://php.net/set_error_handler
 */
function exceptionHandler(\Exception $e)
{
    errorHandler(get_class($e), $e->getMessage(), $e->getFile(), $e->getLine());
}

/**
 * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
 * see comment by Philip http://php.net/set_error_handler
 * from http://php.net/set_error_handler
 * The following error types cannot be handled with a user defined function: E_ERROR, E_PARSE,
 * E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING, and most of E_STRICT raised in
 * the file where set_error_handler() is called.
 */
function checkForFatal()
{
    $error = error_get_last();
    if ($error["type"] == E_ERROR || $error["type"] == E_PARSE || $error["type"] == E_CORE_ERROR || $error["type"] == E_CORE_WARNING) {
        errorHandler($error["type"], $error["message"], $error["file"], $error["line"]);
    }
}