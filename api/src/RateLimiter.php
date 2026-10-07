<?php

declare(strict_types=1);

namespace Arch;

final class RateLimiter
{
    public function __construct(
        private readonly string $directory,
        private readonly int $max,
        private readonly int $window,
    ) {
    }

    /** true — запрос разрешён, false — лимит исчерпан. */
    public function allows(string $key): bool
    {
        if ($this->max <= 0) {
            return true;
        }

        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0775, true);
        }

        $file = $this->directory . '/' . hash('sha256', $key) . '.json';
        $now = time();
        $window = $this->window;

        $timestamps = [];
        $handle = @fopen($file, 'c+');
        if ($handle === false) {
            return true;
        }

        try {
            flock($handle, LOCK_EX);

            $raw = stream_get_contents($handle) ?: '';
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $timestamps = array_values(array_filter(
                    $decoded,
                    static fn (mixed $t): bool => is_int($t) && $t > $now - $window,
                ));
            }

            if (count($timestamps) >= $this->max) {
                return false;
            }

            $timestamps[] = $now;
            rewind($handle);
            ftruncate($handle, 0);
            fwrite($handle, json_encode($timestamps));

            return true;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
