<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $mail;
    private $email_template;

    public function __construct($host = MAIL_HOST, $username = MAIL_USER, $password = MAIL_PASS, $port = MAIL_PORT, $template = 'email', $smtpSecure = PHPMailer::ENCRYPTION_STARTTLS)
    {
        $this->mail = new PHPMailer(true);

        // Server settings
        $this->mail->SMTPDebug = 0;    // Enable verbose debug output
        $this->mail->isSMTP();                          // Send using SMTP
        $this->mail->Host = $host;                      // Set the SMTP server to send through
        $this->mail->SMTPAuth = true;                   // Enable SMTP authentication
        $this->mail->Username = $username;              // SMTP username
        $this->mail->Password = $password;              // SMTP password
        $this->mail->SMTPSecure = $this->secure($smtpSecure);          // Enable implicit TLS encryption
        $this->mail->Port = $port;                      // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`                // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        if ($template !== '') {
            $this->email_template = "email-templates/$template.html";
        } else {
            $this->email_template = '';
        }
    }

    public function secure($type)
    {
        $secure = PHPMailer::ENCRYPTION_STARTTLS;
        switch ($type) {
            case 'starttls':
                $secure = PHPMailer::ENCRYPTION_STARTTLS;
                break;
            case 'smtps':
                $secure = PHPMailer::ENCRYPTION_SMTPS;
                break;

        }
        return $secure;
    }

    public function setFrom($address, $name)
    {
        $this->mail->setFrom($address, $name);
    }

    public function addRecipient($address, $name = null)
    {
        $this->mail->addAddress($address, $name);
    }

    public function addReplyTo($address, $name)
    {
        $this->mail->addReplyTo($address, $name);
    }

    public function addCC($address)
    {
        $this->mail->addCC($address);
    }

    public function addBCC($address)
    {
        $this->mail->addBCC($address);
    }

    public function addAttachment($path, $name = null)
    {
        $this->mail->addAttachment($path, $name);
    }

    public function setHTMLContent($subject, $body, $altBody)
    {
        $this->mail->isHTML(true);          // Set email format to HTML
        if ($this->email_template !== '') {
            $message = file_get_contents($this->email_template);
            $message = str_replace('%subject%', $subject, $message);
            $message = str_replace('%message%', $body, $message);
        } else {
            $message = $body;

        }
        $this->mail->MsgHTML($message);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        $this->mail->AltBody = $altBody;
    }

    public function send()
    {
        try {
            return $this->mail->send();
        } catch (Exception $e) {
            return $this->mail->ErrorInfo;
        }
    }
}

?>