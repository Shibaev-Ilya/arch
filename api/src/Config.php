<?php

declare(strict_types=1);

namespace Arch;

final class Config
{
    private static ?self $instance = null;

    private function __construct(private readonly array $data)
    {
    }

    /** Читает api/config.php, при наличии api/config.local.php накладывает его и переменные окружения. */
    public static function load(string $directory): self
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        /** @var array $data */
        $data = require $directory . '/config.php';

        $local = $directory . '/config.local.php';
        if (is_file($local)) {
            /** @var array $localData */
            $localData = require $local;
            $data = array_replace_recursive($data, $localData);
        }

        $envMap = [
            'APP_DEBUG' => 'debug',
            'APP_ALLOWED_ORIGINS' => 'allowed_origins',
            'MAIL_HOST' => 'mail.host',
            'MAIL_PORT' => 'mail.port',
            'MAIL_ENCRYPTION' => 'mail.encryption',
            'MAIL_USERNAME' => 'mail.username',
            'MAIL_PASSWORD' => 'mail.password',
            'MAIL_FROM_ADDRESS' => 'mail.from.address',
            'MAIL_FROM_NAME' => 'mail.from.name',
            'MAIL_TO' => 'mail.to',
            'RATE_LIMIT_MAX' => 'rate_limit.max',
            'RATE_LIMIT_WINDOW' => 'rate_limit.window',
        ];

        foreach ($envMap as $env => $path) {
            $value = getenv($env);
            if ($value === false || $value === '') {
                continue;
            }

            $data = self::setPath($data, $path, $value);
        }

        if (is_array($data['allowed_origins'] ?? null)) {
            $data['allowed_origins'] = array_values(array_filter(
                array_map('trim', $data['allowed_origins']),
                static fn (string $v): bool => $v !== '',
            ));
        }

        return self::$instance = new self($data);
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    public function get(string $path, mixed $default = null): mixed
    {
        $value = $this->data;
        foreach (explode('.', $path) as $key) {
            if (!is_array($value) || !array_key_exists($key, $value)) {
                return $default;
            }
            $value = $value[$key];
        }

        return $value;
    }

    public function str(string $path, string $default = ''): string
    {
        $value = $this->get($path, $default);

        return is_scalar($value) ? (string) $value : $default;
    }

    public function int(string $path, int $default): int
    {
        $value = $this->get($path, $default);

        return is_numeric($value) ? (int) $value : $default;
    }

    public function bool(string $path, bool $default): bool
    {
        $value = $this->get($path, $default);

        return is_bool($value) ? $value : filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $default;
    }

    /** @return list<string> */
    public function list(string $path, array $default = []): array
    {
        $value = $this->get($path, $default);
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        if (!is_array($value)) {
            return $default;
        }

        return array_values(array_filter(array_map('strval', $value), static fn (string $v): bool => trim($v) !== ''));
    }

    /** @param array<string, mixed> $data */
    private static function setPath(array $data, string $path, mixed $value): array
    {
        $keys = explode('.', $path);
        $ref = &$data;
        foreach ($keys as $key) {
            if (!isset($ref[$key]) || !is_array($ref[$key])) {
                $ref[$key] = [];
            }
            $ref = &$ref[$key];
        }
        $ref = $value;

        return $data;
    }
}
