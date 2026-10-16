<?php

declare(strict_types=1);

function e(string|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function pad2(int $n): string
{
    return sprintf('%02d', $n);
}

/** @return array **/
function data(string $name): array
{
    static $cache = [];

    if (!isset($cache[$name])) {
        $cache[$name] = require __DIR__ . '/data/' . $name . '.php';
    }

    return $cache[$name];
}

function partial(string $name, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    require __DIR__ . '/partials/' . $name . '.php';
}

/** @return array{js: string, css: list<string>} */
function vite_bundle(string $entry = 'src/js/main.js'): array
{
    static $manifest = null;

    if ($manifest === null) {
        $file = __DIR__ . '/../assets/.vite/manifest.json';
        $decoded = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;
        $manifest = is_array($decoded) ? $decoded : [];
    }

    $item = $manifest[$entry] ?? null;

    if (!is_array($item) || !isset($item['file'])) {
        return ['js' => '', 'css' => []];
    }

    // manifest хранит пути относительно outDir=assets → URL: /assets/<file>
    $toUrl = static fn (string $file): string => '/assets/' . ltrim($file, '/');

    return [
        'js' => $toUrl((string) $item['file']),
        'css' => array_values(array_map($toUrl, $item['css'] ?? [])),
    ];
}

function vite_tags(): string
{
    $bundle = vite_bundle();
    $out = '';

    foreach ($bundle['css'] as $href) {
        $out .= '<link rel="stylesheet" href="' . e($href) . '">' . "\n";
    }

    if ($bundle['js'] !== '') {
        $out .= '<script type="module" src="' . e($bundle['js']) . '"></script>' . "\n";
    } else {
        $out .= '<!-- vite: бандл не собран — выполните `npm run build` -->' . "\n";
    }

    return $out;
}
