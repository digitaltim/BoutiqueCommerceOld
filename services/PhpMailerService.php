<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Services;

/**
 * Service Layer for PHPMailer
 */
class PhpMailerService {

    private $defaultFromEmail;
    private $defaultFromName;
    private $protocol;
    private $smtpHost;
    private $smtpPort;
    private $phpMailer;

    public function __construct(string $defaultFromEmail, string $defaultFromName, string $protocol, string $smtpHost = null, int $smtpPort = null)
    {
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
        foreach ($toEmails as $email) {
            $this->phpMailer->addAddress($email);
        }
        if ($fromEmail == null) {
            $fromEmail = $this->defaultFromEmail;
        }
        if ($fromName == null) {
            $fromName = $this->defaultFromName;
        }
        $this->phpMailer->setFrom($fromEmail, $fromName);
        $this->phpMailer->send();
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
                throw new \Exception('bad phpmailerType: '.$type);
        }
        return $m;
    }
}
