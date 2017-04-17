<?php
namespace It_All\BoutiqueCommerce\Utilities;

/**
 * Manages the static phpmailer property which is used to send email
 */
class Mailer {

    private $protocol;
    private $smtpHost;
    private $smtpPort;

    function __construct()
    {
        $this->protocol = $protocol;
        $this->smtpHost = $smtpHost;
        $this->smtpPort = $smtpPort;
    }

    /**
     * property accessed by any script to send email
     */
    static private $phpmailer;

    /**
     * returns the phpmailer object, calling create if not already set, or clear if set to start fresh
     * For scripts that send multiple emails, this may be easier than trying to put the shared instance back into a clean slate before each email.  (You need to clear the "to" addresses, for example.)
     */
    static public function getPhpmailer(string $protocol, string $smtpHost, int $smtpPort) {
        if(!isset(self::$phpmailer)) {
            self::$phpmailer = self::create($protocol, $smtpHost, $smtpPort);
        }
        else {
            self::clear();
        }
        return self::$phpmailer;
    }

    /**
     * clears the current phpmailer
     */
    static private function clear() {
        if (!isset(self::$phpmailer)) {
            return;
        }
        self::$phpmailer->clearAddresses();
        self::$phpmailer->clearCCs();
        self::$phpmailer->clearBCCs();
        self::$phpmailer->clearReplyTos();
        self::$phpmailer->clearAllRecipients();
        self::$phpmailer->clearAttachments();
        self::$phpmailer->clearCustomHeaders();
    }

    /**
     * Creates a fresh mailer with the default "from" address and name.
     */
    static private function create(string $protocol, string $smtpHost, int $smtpPort) {
        $m = new \PHPMailer();
        switch ($protocol) {
            case 'sendmail':
                $m->isSendmail();
                break;
            case 'smtp':
                $m->isSMTP();
                $m->Host = $smtpHost;
                $m->SMTPAuth = false;
                $m->SMTPAutoTLS = false;
                $m->Port = $smtpPort;
                break;
            case 'mail':
                $m->isMail();
                break;
            case 'qmail':
                $m->isQmail();
                break;
            default:
                throw new \Exception('bad phpmailerType: '.$type);
        }
        //$m->setFrom(Config::$defaultFromEmail, Config::$defaultFromName);
        return $m;
    }
}