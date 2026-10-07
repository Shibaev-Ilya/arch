<?php

declare(strict_types=1);

namespace Arch;

final class Http
{
    /** @var list<string> */
    private static array $allowedOrigins = [];

    /** @param list<string> $origins */
    public static function setCORS(array $origins): void
    {
        self::$allowedOrigins = $origins;

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if ($origin === '' || !in_array($origin, $origins, true)) {
            return;
        }

        header('Access-Control-Allow-Origin: ' . $origin);
        header('Vary: Origin');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Max-Age: 600');
    }

    public static function handlePreflight(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }

    public static function requirePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Allow: POST');
            self::json(['ok' => false, 'error' => 'Метод не поддерживается'], 405);
        }
    }

    /** @return array<string, mixed> */
    public static function input(): array
    {
        $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');

        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input') ?: '';
            $data = json_decode($raw, true);

            return is_array($data) ? $data : [];
        }

        return $_POST;
    }

    /** Однострочное значение: переводы строк вырезаются (защита от injection в заголовках). */
    public static function line(array $input, string $key): string
    {
        $value = self::field($input, $key);

        return trim(str_replace(["\r", "\n"], ' ', $value));
    }

    /** Многострочное значение (textarea): нормализуются переводы строк. */
    public static function field(array $input, string $key): string
    {
        $value = $input[$key] ?? '';
        if (!is_scalar($value)) {
            return '';
        }

        $value = str_replace("\0", '', (string) $value);
        $value = str_replace(["\r\n", "\r"], "\n", $value);

        return trim($value);
    }

    public static function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-store');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function clientIp(): string
    {
        $remote = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (!in_array($remote, ['127.0.0.1', '::1'], true)) {
            return $remote;
        }

        $forwarded = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '');
        $first = trim($forwarded[0] ?? '');

        return $first !== '' ? $first : $remote;
    }
}
