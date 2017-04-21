<?php
namespace It_All\BoutiqueCommerce\Utilities;

use It_All\BoutiqueCommerce\Services\Mailer;

class ErrorHandler
{
    private $reportMethods;
    private $logPath;
    private $env;
    private $mailer;

    public function __construct(array $reportMethods, string $logPath, string $env, Mailer $m)
    {
        $this->reportMethods = $reportMethods;
        $this->logPath = $logPath;
        $this->env = $env;
        $this->mailer = $m;
    }

    /**
     * 3 ways to handle:
     * log - always
     * echo - never on live server, depends on config and @ controller on dev
     * email - always on live server, depends on config on dev. never email error deets.
     * Then, die if necessary
     */
    private function handleError(string $message, $die = false)
    {
        $errorMessage = $this->generateMessage($message);

        error_log($errorMessage, 3, $this->logPath);

        // error_reporting() == 0 happens when an expression is prefixed with @ (meaning: ignore errors).
        if (!($this->env == 'live' || error_reporting() == 0 || !in_array('echo', $this->reportMethods))) {
            echo nl2br($errorMessage);
        }

        if ($this->env == 'live' || in_array('email', $this->reportMethods)) {
            $this->email();
        }

        if ($die) {
            die();
        }
    }

    private function generateMessage($errorMessage)
    {
        $message = "[".date('Y-m-d H:i:s e')."]";

        $message .= isRunningFromCommandLine() ? gethostname() : $_SERVER['SERVER_NAME'];

        if (isRunningFromCommandLine()) {
            global $argv;
            $message .= "Command line: " . $argv[0];
        } else {
            $message .= 'Web Page: ' . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'];
            if (strlen($_SERVER['QUERY_STRING']) > 0) {
                $message .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
        $message .= $errorMessage . "\n";
        return $message;
    }

    public function checkForFatal()
    {
        $error = error_get_last();
        if ($error["type"] == E_ERROR || $error["type"] == E_PARSE || $error["type"] == E_CORE_ERROR || $error["type"] == E_CORE_WARNING) {
            extract($error);
            $message = "type: $type\n";
            $message .= "message: $message\n";
            $message .= "file: $file\n";
            $message .= "line: $line\n";
            $this->handleError($message, true);
        }
    }

    /** @param \Throwable $e
     * catches both Errors and Exceptions
     * create error message and send to handleError
     */
    public function throwableHandler(\Throwable $e)
    {
        switch ($e->getCode()) {
            case E_ERROR:
            case E_USER_ERROR:
                $errorTypeStr = 'Fatal Error';
                $exitPage = true;
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $errorTypeStr = 'Warning';
                $exitPage = false;
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $errorTypeStr = "Notice";
                $exitPage = false;
                break;
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $errorTypeStr = "Deprecated";
                $exitPage = false;
                break;
            default:
                $errorTypeStr = "Unknown error type";
                $exitPage = false;
        }

        $traceString = "";
        foreach ($e->getTrace() as $k => $v) {
            $traceString .= "#$k ";
            $traceString .= arrayWalkToStringRecursive($v);
            $traceString .= "\n";
            $ct++;
        }

        $message = $errorTypeStr;
        $message .= "\n".$e->getMessage();
        $message .= "\n".$e->getFile();
        $message .= " Line ".$e->getLine();
        $message .= "\nStack Trace:\n".$traceString;

        //echo "\ngetTraceAsString: ".$e->getTraceAsString();

        $this->handleError($message, $exitPage);
    }

    private function email()
    {
        $this->mailer->send($_SERVER['SERVER_NAME'] . " Error", "Check log file for details.", ['greg@it-all.com']);
    }

}
