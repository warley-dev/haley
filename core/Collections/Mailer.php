<?php

namespace Haley\Collections;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    private array|null $from = null;
    private array $recipients = [];
    private string|null $subject = null;

    private string|null $content = null;
    private array|null $view = null;
    private array $attachments = [];
    private bool $html = true;

    /**
     * Set from
     */
    public function from(string $email, string|null $name = null)
    {
        $this->from = [
            'email' => $email,
            'name' => $name
        ];
    }

    /**
     * Add recipient
     */
    public function recipient(string $email, string|null $name = null)
    {
        $this->recipients[] = [
            'email' => $email,
            'name' => $name
        ];
    }

    /**
     * Set subject
     */
    public function subject(string|null $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Content view
     */
    public function view(string $view, array|object $params = [], string|null $path = null)
    {
        $this->html = true;
        $this->content = null;

        $this->view = [
            'view' => $view,
            'params' => $params,
            'path' => $path
        ];
    }

    /**
     * Content text
     */
    public function content(string $content, bool $html = true)
    {
        $this->html = $html;
        $this->content = $content;
        $this->view = null;
    }

    /**
     * Add attachment
     */
    public function attachments(string $path, string|null $name = null)
    {
        $this->attachments[] = [
            'path' => $path,
            'name' => $name
        ];
    }

    /**
     * Reset params
     */
    public function reset()
    {
        $this->from = null;
        $this->recipients = [];
        $this->subject = null;
        $this->content = null;
        $this->view = null;
        $this->attachments = [];
        $this->html = true;

        return $this;
    }

    /**
     * Send email
     */
    public function send()
    {
        $mail = new PHPMailer();

        // server settings
        $mail->Host = env('MAIL_HOST', '');
        $mail->Username = env('MAIL_USERNAME', '');
        $mail->Password = env('MAIL_PASSWORD', '');
        $mail->Port = env('MAIL_PORT');
        $mail->CharSet = 'UTF-8';

        if (env('MAIL_SMTP')) {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
        }

        // from
        if ($this->from) {
            $mail->setFrom($this->from['email'], $this->from['name'] ?? '');
        } else {
            $mail->setFrom(env('MAIL_FROM_ADDRESS', ''), env('MAIL_FROM_NAME', ''));
        }

        // recipients
        foreach ($this->recipients as $recipient) {
            $mail->addAddress($recipient['email'], $recipient['name'] ?? '');
        }

        // attachments
        foreach ($this->attachments as $attachment) {
            $mail->addAttachment($attachment['path'], $attachment['name'] ?? '');
        }

        // content
        $mail->isHTML($this->html);

        $mail->Subject = $this->subject ?? '';

        if ($this->content !== null) {
            $mail->Body = $this->content;
        } else if ($this->view !== null) {
            $mail->Body = view($this->view['view'], $this->view['params'], false, $this->view['path']);
        } else {
            $mail->Body = '';
        }

        return $mail->send();
    }
}
