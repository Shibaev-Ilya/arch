# arch.local — бэкенд (PHP 8.4) + статика Next.js

Структура проекта (по образцу `quick-start-landing`):

```
home/arch/                  ← корень сайта arch.local (nginx + Apache/mod_php)
  index.php                 ← единая точка входа
  index.html, _next/, ...   ← статическая сборка Next.js (npm run build)
  api/
    config.php              ← настройки (SMTP, лимиты, CORS)
    config.local.php        ← локальные переопределения (не коммитится)
    src/                    ← классы (Config, Http, RateLimiter, MailerService, ContactHandler)
    vendor/                 ← PHPMailer 6.9.3
  arch/                     ← исходники Next.js
  .osp/project.ini          ← домен arch.local, PHP-8.4
```

## Точка входа

`index.php` — как в quick-start-landing:

- `POST /index.php?action=contact` — заявка с формы, письмо через PHPMailer (SMTP), JSON-ответ;
- `GET` — раздача статики Next.js (обычно её отдаёт nginx напрямую, index.php подхватывает только если запрос дошёл до него).

Ответы: `200 {"ok":true}` · `405` не-POST · `422` ошибки валидации (`fields`) · `429` лимит по IP · `500` ошибка SMTP (текст — только при `debug`).

Защита: honeypot-поле `website`, лимит `rate_limit` заявок с IP, CORS по `allowed_origins`, вырезание переводов строк в однострочных полях.

## Настройки

`api/config.php` (массив): `mail.*` — SMTP, `mail.to` — куда приходят заявки, `rate_limit.*`, `allowed_origins`, `debug`.

Переопределение: файл `api/config.local.php` с тем же форматом (накладывается поверх) либо переменные окружения `MAIL_HOST`, `MAIL_PORT`, `MAIL_ENCRYPTION`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, `MAIL_TO`, `APP_DEBUG`, `APP_ALLOWED_ORIGINS`, `RATE_LIMIT_MAX`, `RATE_LIMIT_WINDOW`.

Локально SMTP уходит в Smtp4dev OSPanel (`127.0.0.1:25`, без аутентификации) — письма видны в его веб-интерфейсе.

## Сборка статики

```bash
cd arch
npm run build     # next build (output: export) + копия out/ в корень сайта
```

В dev (`npm run dev`) форма ходит на `/index.php?action=contact`, а `next.config.ts` проксирует запрос на `http://arch.local/index.php` (работает при запущенном OSPanel; цель переопределяется `API_PROXY_TARGET`).

В эксплуатации (nginx отдаёт статику с того же домена) прокси не нужен — запрос same-origin.

## Добавление новых форм/действий

1. Создайте `api/src/<Name>Handler.php` по образцу `ContactHandler.php`.
2. Добавьте ветку `match ($action)` в `index.php`.
3. В форме: `fetch("/index.php?action=<name>", { method: "POST", ... })`.
