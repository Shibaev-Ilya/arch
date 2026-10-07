<?php

declare(strict_types=1);

namespace Arch;

final class ContactHandler
{
    public static function handle(Config $config): never
    {
        Http::requirePost();

        $input = Http::input();

        // Honeypot: скрытое поле заполняют только боты — отвечаем успехом, но письмо не шлём.
        if (Http::line($input, 'website') !== '') {
            Http::json(['ok' => true]);
        }

        $limiter = new RateLimiter(
            sys_get_temp_dir() . '/arch-api-rate',
            $config->int('rate_limit.max', 5),
            $config->int('rate_limit.window', 600),
        );

        $clientIp = Http::clientIp();

        if (!$limiter->allows($clientIp)) {
            Http::json(['ok' => false, 'error' => 'Слишком много заявок. Попробуйте позже.'], 429);
        }

        $name = Http::line($input, 'name');
        $contact = Http::line($input, 'contact');
        $task = Http::field($input, 'task');

        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Укажите имя';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'Имя слишком длинное (макс. 100 символов)';
        }

        if ($contact === '') {
            $errors['contact'] = 'Укажите телефон или e-mail';
        } elseif (mb_strlen($contact) > 200) {
            $errors['contact'] = 'Контакт слишком длинный (макс. 200 символов)';
        } elseif (str_contains($contact, '@') && !filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $errors['contact'] = 'Проверьте e-mail';
        }

        if (mb_strlen($task) > 5000) {
            $errors['task'] = 'Описание задачи слишком длинное (макс. 5000 символов)';
        }

        if ($errors !== []) {
            Http::json(['ok' => false, 'error' => 'Проверьте заполнение формы.', 'fields' => $errors], 422);
        }

        $site = preg_replace('/[^a-zA-Z0-9.\-]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));

        try {
            MailerService::fromConfig($config)->sendContact(
                ['name' => $name, 'contact' => $contact, 'task' => $task],
                $clientIp,
                (string) ($_SERVER['HTTP_USER_AGENT'] ?? '-'),
                $site !== '' ? $site : 'localhost',
            );
        } catch (Throwable $e) {
            error_log('[arch] send failed: ' . $e->getMessage());

            Http::json([
                'ok' => false,
                'error' => $config->bool('debug', false)
                    ? 'Ошибка отправки: ' . $e->getMessage()
                    : 'Не удалось отправить заявку. Попробуйте позже или свяжитесь напрямую.',
            ], 500);
        }

        Http::json(['ok' => true]);
    }
}
