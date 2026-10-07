<?php

declare(strict_types=1);

/**
 * arch.local — единая точка входа.
 *
 * POST /index.php?action=form — заявка с формы.
 *   · fetch (Accept: application/json) → JSON-ответ как у api/.
 *   · обычный HTML-post без JS        → 303-редирект на /?sent=1 | /?error=…
 * GET  / и /index.php                — рендер страницы.
 * GET  /assets/*, /projects/*, /favicon.ico — статика.
 * всё остальное                      — 404.
 */

require __DIR__ . '/api/src/bootstrap.php';
require __DIR__ . '/templates/functions.php';

use Arch\Config;
use Arch\ContactHandler;
use Arch\Http;

$config = Config::load(__DIR__ . '/api');

Http::setCORS($config->list('allowed_origins', ['http://localhost:3000']));
Http::handlePreflight();

function render_page(string $template, int $status = 200): never
{
    http_response_code($status);

    $title = 'Марактаева Наталья — архитектор-конструктор · АР, КР, РД';
    $description = 'Проектирую здания под ключ: архитектурные решения, '
        . 'конструктив и рабочая документация. Частные и общественные здания, '
        . 'экспертиза, сопровождение стройки.';

    ob_start();
    require __DIR__ . '/templates/' . $template . '.php';
    $content = (string) ob_get_clean();

    require __DIR__ . '/templates/layout.php';
    exit;
}

function serve_static(string $rel): never
{
    $isRootFile = !str_contains($rel, '/');
    $isAsset = (bool) preg_match('#^(?:assets|projects)/#', $rel);

    if (!$isAsset && !($isRootFile && $rel === 'favicon.ico')) {
        render_page('404', 404);
    }

    if (str_contains($rel, '..') || str_ends_with(strtolower($rel), '.php')) {
        render_page('404', 404);
    }

    $file = __DIR__ . '/' . $rel;

    if (!is_file($file)) {
        render_page('404', 404);
    }

    static $mimes = [
        'js' => 'text/javascript; charset=utf-8',
        'mjs' => 'text/javascript; charset=utf-8',
        'css' => 'text/css; charset=utf-8',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'woff2' => 'font/woff2',
        'json' => 'application/json; charset=utf-8',
        'txt' => 'text/plain; charset=utf-8',
    ];

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $type = $mimes[$ext] ?? (mime_content_type($file) ?: 'application/octet-stream');

    header('Content-Type: ' . $type);
    header('Content-Length: ' . (string) filesize($file));
    readfile($file);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = (string) ($_GET['action'] ?? '');
$uri = rawurldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

/* ---------- POST: приём заявки ---------- */

if ($method === 'POST') {
    if ($action !== 'form') {
        Http::json(['ok' => false, 'error' => 'Неизвестный запрос'], 404);
    }

    $accept = strtolower((string) ($_SERVER['HTTP_ACCEPT'] ?? ''));
    $isFetch = str_contains($accept, 'application/json')
        || strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';

    if ($isFetch) {
        ContactHandler::handle($config); // JSON и exit
    }

    // Fallback без JS: перехватываем JSON-ответ ContactHandler и уводим в 303-редирект.
    ob_start();
    register_shutdown_function(static function (): void {
        $raw = (string) ob_get_clean();
        $data = json_decode($raw, true);

        if (is_array($data) && ($data['ok'] ?? null) === true) {
            header('Location: /?sent=1', true, 303);
            exit;
        }

        $error = is_array($data) ? (string) ($data['error'] ?? '') : '';
        if ($error === '') {
            $error = 'Не удалось отправить заявку. Попробуйте позже или свяжитесь напрямую.';
        }

        header('Location: /?error=' . rawurlencode($error), true, 303);
        exit;
    });

    ContactHandler::handle($config);
}

/* ---------- прочие методы ---------- */

if ($method !== 'GET' && $method !== 'HEAD') {
    Http::json(['ok' => false, 'error' => 'Метод не поддерживается'], 405);
}

/* ---------- GET: маршрутизация ---------- */

if (str_contains($uri, '..')) {
    render_page('404', 404);
}

$rel = ltrim($uri, '/');

if ($rel === '' || $rel === 'index.php') {
    render_page('home', 200);
}

if ($rel !== '' && (str_contains($rel, '/') || str_contains($rel, '.'))) {
    serve_static($rel);
}

render_page('404', 404);
