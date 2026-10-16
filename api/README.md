# arch.local — бэкенд (PHP 8.4)

Структура проекта:

```
home/arch/                  ← корень сайта arch.local (nginx + Apache/mod_php)
  index.php                 ← роутер/единая точка входа
  templates/                ← PHP-шаблоны (layout, home, 404, partials/, data/)
  api/
    config.php              ← настройки (SMTP, лимиты, CORS, debug)
    config.local.php        ← локальные переопределения (в .gitignore)
    composer.json           ← php >=8.4, phpmailer ^6.9
    src/                    ← bootstrap.php + классы (Config, Http, RateLimiter,
                              MailerService, ContactHandler)
    vendor/                 ← PHPMailer 6.9.3
  src/, assets/             ← исходники фронта и сборка Vite (см. корневой README)
  projects/                 ← SVG-обложки проектов (статика)
  .osp/project.ini          ← домен arch.local, PHP-8.4
```

## Точка входа

`index.php` подключает `api/src/bootstrap.php` (autoload vendor, require классов,
`display_errors` по `debug`), затем маршрутизирует:

- `POST /index.php?action=form` — заявка с формы. При `Accept: application/json`
  или `X-Requested-With: XMLHttpRequest` (fetch) — JSON-ответ; обычный HTML-post
  без JS — перехват ответа и 303-редирект на `/?sent=1` | `/?error=…`;
- `GET /`, `/index.php` — рендер шаблона; `GET /assets/*`, `/projects/*`,
  `/favicon.ico` — статика (файловый доступ, MIME по расширению); всё прочее — 404;
- прочие методы — `405`.

Ответы: `200 {"ok":true}` · `404` неизвестный `action` (`{"ok":false,"error":"Неизвестный запрос"}`) ·
`405` не-GET/POST · `422` валидация (`error` + `fields`: `name`, `contact`, `task`) ·
`429` лимит по IP · `500` ошибка SMTP (текст — только при `debug`).

Поля формы: `name` (обяз., ≤100), `contact` (обяз., ≤200, e-mail проверяется
`FILTER_VALIDATE_EMAIL`), `task` (≤5000), honeypot `website`.

Защита: honeypot `website` (отвечает успехом, письмо не шлётся), RateLimiter по IP
(по умолчанию 5 заявок за 600 с, файлы в `sys_get_temp_dir()/arch-api-rate`,
блокировка `flock`), CORS по `allowed_origins` + preflight `OPTIONS` → `204`,
вырезание переводов строк в однострочных полях, `X-Content-Type-Options: nosniff`.
IP берётся из `X-Forwarded-For` для localhost, иначе `REMOTE_ADDR`.

## Настройки

`api/config.php` (массив): `debug`, `allowed_origins`, `mail.*` — `host`, `port`,
`encryption` (`tls` | `ssl` | `''`), `username`, `password`, `from.address`,
`from.name`, `to`, и `rate_limit.max` / `rate_limit.window`.

Переопределение: файл `api/config.local.php` с тем же форматом (накладывается через
`array_replace_recursive`) либо переменные окружения `MAIL_HOST`, `MAIL_PORT`,
`MAIL_ENCRYPTION`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`,
`MAIL_FROM_NAME`, `MAIL_TO`, `APP_DEBUG`, `APP_ALLOWED_ORIGINS`,
`RATE_LIMIT_MAX`, `RATE_LIMIT_WINDOW`.

Локально SMTP уходит в Smtp4dev OSPanel (`127.0.0.1:25`, без аутентификации) —
письма видны в его веб-интерфейсе.

## Классы `api/src`

- `bootstrap.php` — autoload + require классов + `display_errors`/`error_reporting`;
- `Config` — `load()`: `config.php` → `config.local.php` → env; геттеры `str/int/bool/list`;
- `Http` — CORS/preflight, `requirePost()`, `input()` (JSON или `$_POST`),
  `line()`/`field()` (нормализация), `json()` (never), `clientIp()`;
- `RateLimiter` — скользящее окно, JSON-файл на ключ (sha256 IP);
- `MailerService` — письмо через PHPMailer (plain text, тема
  «Заявка с сайта `<host>` — `<имя>`», `Reply-To` если контакт — e-mail,
  в теле: имя, контакт, задача, IP, время, User-Agent);
- `ContactHandler` — валидация → RateLimiter → отправка → JSON.

## Сборка и запуск

```bash
npm run build       # фронт в assets/ (см. корневой README)
php -l index.php    # линт
php -S 127.0.0.1:8090 -t . index.php   # локальный запуск без OSPanel
```

## Добавление новых форм/действий

1. Создайте `api/src/<Name>Handler.php` по образцу `ContactHandler.php` и
   подключите его в `api/src/bootstrap.php`.
2. Добавьте ветку `match`/`if` по `$action` в `index.php` (POST-блок).
3. В форме: `action="/index.php?action=<name>"`, JS —
   `fetch(form.action, { method: "POST", headers: { Accept: "application/json" } })`.
