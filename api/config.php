<?php

/**
 * Настройки бэкенда arch.local.
 * Для боевых значений создайте рядом config.local.php с тем же форматом
 * (он накладывается поверх и попадает в .gitignore) — либо переопределяйте
 * переменными окружения MAIL_*, APP_*, RATE_LIMIT_*.
 */
return [
    // Показывать тексты ошибок отправки в ответе
    'debug' => true,

    // Origins, которым разрешён кросс-доменный запрос (CORS)
    'allowed_origins' => [
        'http://localhost:3000',
        'http://arch.local',
        'https://arch.local',
    ],

    // SMTP. Локально — Smtp4dev из OSPanel (127.0.0.1:25, без аутентификации)
    'mail' => [
        'host' => '127.0.0.1',
        'port' => 25,
        // tls | ssl | '' (без шифрования)
        'encryption' => '',
        'username' => '',
        'password' => '',
        'from' => [
            'address' => 'site@arch.local',
            'name' => 'Сайт arch.local',
        ],
        // Куда приходят заявки
        'to' => 'info@arch.local',
    ],

    // Анти-спам: не более max заявок с одного IP за window секунд
    'rate_limit' => [
        'max' => 5,
        'window' => 600,
    ],
];
