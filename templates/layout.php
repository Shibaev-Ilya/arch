<?php

/** @var string $content */
/** @var string $title */
/** @var string $description */

$title ??= 'Марактаева Наталья — архитектор-конструктор · АР, КР, РД';
$description ??= 'Проектирую здания под ключ: архитектурные решения, конструктив и рабочая документация. Частные и общественные здания, экспертиза, сопровождение стройки.';

?>
<!doctype html>
<html lang="ru">
<head>
<script>document.documentElement.classList.add("js")</script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($description) ?>">
<meta name="theme-color" content="#f5f3ef">
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap">
<?= vite_tags() ?>
<noscript>
  <style>
    .header__menu { max-height: none !important; overflow: visible; }
  </style>
</noscript>
</head>
<body>
<?php partial('header'); ?>
<main>
<?= $content ?>
</main>
<?php partial('footer'); ?>
</body>
</html>
