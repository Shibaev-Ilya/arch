<?php

declare(strict_types=1);

namespace Arch;

use PHPMailer\PHPMailer\PHPMailer;

final class MailerService
{
    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly string $encryption,
        private readonly string $username,
        private readonly string $password,
        private readonly string $fromAddress,
        private readonly string $fromName,
        private readonly string $toAddress,
    ) {
    }

    public static function fromConfig(Config $config): self
    {
        $from = $config->str('mail.from.address');
        $to = $config->str('mail.to');

        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Некорректный mail.from.address');
        }
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('mail.to не задан или некорректен');
        }

        return new self(
            host: $config->str('mail.host', '127.0.0.1'),
            port: $config->int('mail.port', 25),
            encryption: strtolower($config->str('mail.encryption')),
            username: $config->str('mail.username'),
            password: $config->str('mail.password'),
            fromAddress: $from,
            fromName: $config->str('mail.from.name', 'Сайт'),
            toAddress: $to,
        );
    }

    /** @param array{name: string, contact: string, task: string} $data */
    public function sendContact(array $data, string $clientIp, string $userAgent, string $site): void
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->WordWrap = 100;

        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->Port = $this->port;
        $mail->SMTPAuth = $this->username !== '';
        $mail->Username = $this->username;
        $mail->Password = $this->password;

        if ($this->encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($this->encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

        $mail->setFrom($this->fromAddress, $this->fromName);
        $mail->addAddress($this->toAddress);

        if (filter_var($data['contact'], FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($data['contact'], $data['name']);
        }

        $mail->Subject = 'Заявка с сайта ' . $site . ' — ' . $data['name'];
        $mail->isHTML(false);
        $mail->Body = $this->buildBody($data, $clientIp, $userAgent, $site);

        $mail->send();
    }

    /** @param array{name: string, contact: string, task: string} $data */
    private function buildBody(array $data, string $clientIp, string $userAgent, string $site): string
    {
        $lines = [
            'Новая заявка с сайта ' . $site,
            '',
            'Имя: ' . $data['name'],
            'Контакт: ' . $data['contact'],
            'Задача:',
            $data['task'] !== '' ? $data['task'] : '—',
            '',
            '---',
            'IP: ' . $clientIp,
            'Время: ' . date('Y-m-d H:i:s'),
            'User-Agent: ' . $userAgent,
        ];

        return implode("\r\n", $lines);
    }
}
