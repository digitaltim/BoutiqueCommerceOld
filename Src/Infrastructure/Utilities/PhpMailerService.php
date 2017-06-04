<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Utilities;

/**
 * Service Layer for PHPMailer
 */
class PhpMailerService {

    private $logPath;
    private $defaultFromEmail;
    private $defaultFromName;
    private $protocol;
    private $smtpHost;
    private $smtpPort;
    private $phpMailer;

    public function __construct(string $logPath, string $defaultFromEmail, string $defaultFromName, string $protocol, string $smtpHost = null, int $smtpPort = null)
    {
        $this->logPath = $logPath;
        $this->defaultFromEmail = $defaultFromEmail;
        $this->defaultFromName = $defaultFromName;
        $this->protocol = $protocol;
        $this->smtpHost = $smtpHost;
        $this->smtpPort = $smtpPort;
        $this->phpMailer = $this->create();
    }

    public function send(string $subject, string $body, array $toEmails, string $fromEmail = null, string $fromName = null)
    {
        $this->phpMailer->Subject = $subject;
        $this->phpMailer->Body = $body;
        $toEmailsString = '';
        foreach ($toEmails as $email) {
            $toEmailsString .= "$email ";
            $this->phpMailer->addAddress($email);
        }
        if ($fromEmail == null) {
            $fromEmail = $this->defaultFromEmail;
        }
        if ($fromName == null) {
            $fromName = $this->defaultFromName;
        }
        $this->phpMailer->setFrom($fromEmail, $fromName);
        if (!$this->phpMailer->send()) {
            // note do not throw exception here because could get stuck in loop trying to email
            $errorMessage = "\nPhpMailer::send() failed: ".$this->phpMailer->ErrorInfo."\n".
                "subject: $subject\n".
                "body: $body\n".
                "to: $toEmailsString\n".
                "from: $fromEmail\n";
                error_log($errorMessage, 3, $this->logPath);
        }
        $this->clear();
    }

    /**
     * clears the current phpmailer
     */
    private function clear() {
        if (!isset($this->phpMailer)) {
            return;
        }
        $this->phpMailer->clearAddresses();
        $this->phpMailer->clearCCs();
        $this->phpMailer->clearBCCs();
        $this->phpMailer->clearReplyTos();
        $this->phpMailer->clearAllRecipients();
        $this->phpMailer->clearAttachments();
        $this->phpMailer->clearCustomHeaders();
    }

    /**
     * Creates a fresh mailer
     */
    private function create() {
        $m = new \PHPMailer();
        switch ($this->protocol) {
            case 'sendmail':
                $m->isSendmail();
                break;
            case 'smtp':
                $m->isSMTP();
                $m->Host = $this->smtpHost;
                $m->SMTPAuth = false;
                $m->SMTPAutoTLS = false;
                $m->Port = $this->smtpPort;
                break;
            case 'mail':
                $m->isMail();
                break;
            case 'qmail':
                $m->isQmail();
                break;
            default:
                throw new \Exception('bad phpmailerType: '.$this->protocol);
        }
        return $m;
    }
}
