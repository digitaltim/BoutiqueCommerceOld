<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Utilities;

use It_All\BoutiqueCommerce\Services\Mailer;

class ErrorHandler
{
    private $logPath;
    private $isLiveServer;
    private $echoDev;
    private $emailDev;
    private $mailer;
    private $emailTo;

    public function __construct(string $logPath, bool $isLiveServer, bool $echoDev, bool $emailDev, Mailer $m, string $emailTo)
    {
        $this->logPath = $logPath;
        $this->isLiveServer = $isLiveServer;
        $this->echoDev = $echoDev;
        $this->emailDev = $emailDev;
        $this->mailer = $m;
        $this->emailTo = $emailTo;
    }

    /**
     * 3 ways to handle:
     * log - always
     * echo - never on live server, depends on config and @ controller on dev
     * email - always on live server, depends on config on dev. never email error deets.
     * Then, die if necessary
     */
    private function handleError(string $messageBody, $die = false)
    {
        $errorMessage = $this->generateMessage($messageBody);

        // log
        error_log($errorMessage, 3, $this->logPath);

        // echo
        // error_reporting() == 0 happens when an expression is prefixed with @ (meaning: ignore errors).
        if (error_reporting() != 0 || (!$this->isLiveServer && $this->echoDev)) {
            echo nl2br($errorMessage, false);
        }

        // email
        if ($this->isLiveServer || $this->emailDev) {
            $this->email();
        }

        if ($die) {
            die();
        }
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

    public function checkForFatal()
    {
        $error = error_get_last();
        $this->handleError($this->generateMessageBodyCommon($error["type"], $error["message"], $error["file"], $error["line"]),true);
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

        $message .= "\nStack Trace:\n".$traceString;

        $this->handleError($message, $exitPage);
    }

    /**
     * @param int $number
     * @param string $string
     * @param string|null $file
     * @param string|null $line
     * to be registered with php's set_error_handler()
     */
    public function phpErrorHandler(int $errno, string $errstr, string $errfile = null, string $errline = null)
    {
        $this->handleError(
            $this->generateMessageBodyCommon($errno, $errstr, $errfile, $errline),
            false
        );
    }

    private function email()
    {
        $this->mailer->send($_SERVER['SERVER_NAME'] . " Error", "Check log file for details.", [$this->emailTo]);
    }

}
