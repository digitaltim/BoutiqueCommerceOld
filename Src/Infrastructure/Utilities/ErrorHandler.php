<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Utilities;

class ErrorHandler
{
    private $logPath;
    private $redirectPage;
    private $isLiveServer;
    private $emailDev;
    private $mailer;
    private $emailTo;
    private $fatalMessage;

    public function __construct(
        string $logPath,
        string $redirectPage,
        bool $isLiveServer,
        bool $emailDev,
        PhpMailerService $m,
        array $emailTo,
        $fatalMessage = 'Apologies, there has been an error on our site. We have been alerted and will correct it as soon as possible.'
    )
    {
        $this->logPath = $logPath;
        $this->redirectPage = $redirectPage;
        $this->isLiveServer = $isLiveServer;
        $this->emailDev = $emailDev;
        $this->mailer = $m;
        $this->emailTo = $emailTo;
        $this->fatalMessage = $fatalMessage;
    }

    /**
     * 3 ways to handle:
     * log - always
     * echo - never on live server, depends on config and @ controller on dev
     * email - always on live server, depends on config on dev. never email error deets.
     * Then, die if necessary
     */
    private function handleError(string $messageBody, bool $die = false)
    {
        // happens when an expression is prefixed with @ (meaning: ignore errors).
        if (error_reporting() == 0) {
            return;
        }
        $errorMessage = $this->generateMessage($messageBody);

        // log
        error_log($errorMessage, 3, $this->logPath);

        // email
        if ($this->isLiveServer || $this->emailDev) {
            $this->email();
        }

        // echo
        if (!$this->isLiveServer) {
            echo nl2br($errorMessage, false);
            if ($die) {
                die();
            }
        }

        if ($die) {
            $_SESSION['notice'] = [$this->fatalMessage, 'error'];
            header("Location: https://$this->redirectPage");
            exit();
        }
    }

//    private function renderError(string $errorMessage)
//    {
//        echo <<< EOT
//<!doctype html>
//<html lang="en">
//    <head>
//        <meta charset="utf-8"/>
//        <title>{{ Error }}</title>
//        <meta http-equiv="X-UA-Compatible" content="IE=edge">
//        <meta name="viewport" content="width=device-width, initial-scale=1.0">
//        <meta name="robots" content="noindex, nofollow">
//        <link href='/css/home.css' rel='stylesheet' type='text/css'>
//    </head>
//    <body>
//        $errorMessage
//    </body>
//</html>
//EOT;
//    }

    /**
     * used in register_shutdown_function to see if a fatal error has occured and handle it.
     * note, this does not occur often in php7, as almost all errors are now exceptions and will be caught by the registered exception handler. fatal errors can still occur for conditions like out of memory: https://trowski.com/2015/06/24/throwable-exceptions-and-errors-in-php7/
     */
    public function checkForFatal()
    {
        $error = error_get_last();
        $fatalErrorTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR];
        if (in_array($error["type"], $fatalErrorTypes)) {
            $this->handleError($this->generateMessageBodyCommon($error["type"], $error["message"], $error["file"], $error["line"]),true);
        }
    }

    /** @param \Throwable $e
     * catches both Errors and Exceptions
     * create error message and send to handleError
     */
    public function throwableHandler(\Throwable $e)
    {
        $message = $this->generateMessageBodyCommon($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
        $exitPage = ($e->getCode() == E_ERROR || $e->getCode() == E_USER_ERROR) ? true : false;

        $traceString = "";
        foreach ($e->getTrace() as $k => $v) {
            $traceString .= "#$k ";
            $traceString .= arrayWalkToStringRecursive($v);
            $traceString .= "\n";
        }

        $message .= "\nStack Trace:\n".str_replace('/media/gcat/storage/it-all.com/Software/ProjectsSrc/BoutiqueCommerce', '', $traceString);

        $this->handleError($message, $exitPage);
    }

    /**
     * @param int $errno
     * @param string $errstr
     * @param string|null $errfile
     * @param string|null $errline
     * to be registered with php's set_error_handler()
     * trigger_error() will call this
     * called for php Notices and possibly more
     */
    public function phpErrorHandler(int $errno, string $errstr, string $errfile = null, string $errline = null)
    {
        $this->handleError(
            $this->generateMessageBodyCommon($errno, $errstr, $errfile, $errline),
            false
        );
    }

    private function generateMessage(string $messageBody): string
    {
        $message = "[".date('Y-m-d H:i:s e')."] ";

        $message .= isRunningFromCommandLine() ? gethostname() : $_SERVER['SERVER_NAME'];

        if (isRunningFromCommandLine()) {
            global $argv;
            $message .= "Command line: " . $argv[0];
        } else {
            $message .= "\nWeb Page: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'];
            if (strlen($_SERVER['QUERY_STRING']) > 0) {
                $message .= "?" . $_SERVER['QUERY_STRING'];
            }
        }
        $message .= "\n" . $messageBody . "\n\n";
        return $message;
    }

    private function getErrorType($errno)
    {
        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                return 'Fatal Error';
            case E_WARNING:
            case E_USER_WARNING:
                return 'Warning';
            case E_NOTICE:
            case E_USER_NOTICE:
                return 'Notice';
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return 'Deprecated';
            case E_PARSE:
                return 'Parse Error';
            case E_CORE_ERROR:
                return 'Core Error';
            case E_CORE_WARNING:
                return 'Core Warning';
            default:
                return 'Unknown error type';
        }

    }

    /**
     * @param int $errno
     * @param string $errstr
     * @param string|null $errfile
     * @param null $errline
     * @return string
     * errline seems to be passed in as a string or int depending on where it's coming from
     */
    private function generateMessageBodyCommon(int $errno, string $errstr, string $errfile = null, $errline = null): string
    {
        $message = $this->getErrorType($errno).": ";
        $message .= "$errstr\n";

        if (!is_null($errfile)) {
            $message .= "$errfile";
            // note it only makes sense to have line if we have file
            if (!is_null($errline)) {
                $message .= " line: $errline";
            }
        }

        return $message;
    }

    private function email()
    {
        $this->mailer->send($_SERVER['SERVER_NAME'] . " Error", "Check log file for details.", $this->emailTo);
    }

}
