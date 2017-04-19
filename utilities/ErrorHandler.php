<?php
namespace It_All\BoutiqueCommerce\Utilities;

class ErrorHandler
{
    private $reportMethods;
    private $logPath;
    private $env;

    public function __construct(array $reportMethods, string $logPath, string $env)
    {
        $this->reportMethods = $reportMethods;
        $this->logPath = $logPath;
        $this->env = $env;
    }

    public function __invoke($request, $response, $args)
    {
        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong!');
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
            echo $errorMessage;
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
        $message .= $errorMessage . "\n\r";
        return $message;
    }

    public function checkForFatal()
    {
        $error = error_get_last();
        if ($error["type"] == E_ERROR || $error["type"] == E_PARSE || $error["type"] == E_CORE_ERROR || $error["type"] == E_CORE_WARNING) {
            extract($error);
            $message = "type: $type<br>";
            $message .= "message: $message<br>";
            $message .= "file: $file<br>";
            $message .= "line: $line<br>";
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
            $traceString .= "<br>";
            $ct++;
        }

        $message = $errorTypeStr;
        $message .= "<br>".$e->getMessage();
        $message .= "<br>".$e->getFile();
        $message .= " Line ".$e->getLine();
        $message .= "<br>Stack Trace:<br>".$traceString;

        //echo "<br>getTraceAsString: ".$e->getTraceAsString();

        $this->handleError($message, $exitPage);
    }

    private function email()
    {
        // todo fix this to use new mailer code

//        $m = \It_All\BoutiqueCommerce\Utilities\Mailer::getPhpmailer($this->config['phpmailer']['protocol'], $this->config['phpmailer']['smtpHost'], $this->config['phpmailer']['smtpPort']);
//        $m->Subject = $_SERVER['SERVER_NAME'] . " Error";
//        $m->Body = "Check log file for details.";
//        $m->addAddress('greg@it-all.com');
//        $m->send();
    }

}
