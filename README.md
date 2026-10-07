# arch.local

Сайт-визитка архитектора-конструктора. Одностраничник на PHP-шаблонах + Vite
(Tailwind CSS v4, SCSS, Swiper), без фреймворков на фронтенде.

## Стек

- **PHP 8.4** (OpenServer) — рендер страницы, раздача статики, приём формы.
  Форма работает через `api/` (валидация, RateLimiter, PHPMailer) и **без JS**
  (303-редирект на `/?sent=1` | `/?error=…`).
- **Vite 8** — сборка CSS/JS в `assets/` c manifest.
- **Tailwind CSS v4** (`@tailwindcss/vite`) — утилиты и токены (`@theme`).
- **SCSS** — кастомные компоненты в `@layer components`.
- **Swiper 14** — карусель отзывов и DIY-лайтбокс галереи проектов.

## Команды

```bash
npm ci              # зависимости
npm run build       # продовая сборка в assets/
npm run dev         # пересборка в watch-режиме
npm run gen:images  # перегенерировать SVG-обложки проектов (./projects)
```

PHP линт: `php -l index.php` и `php -l templates/*.php`.
Локальный запуск (роутер): `php -S 127.0.0.1:8090 -t . index.php`.

## Структура

```
index.php               роутер: страница / статика / форма / 404
templates/
  layout.php            HTML-каркас (head, шапка, футер, vite_tags())
  home.php, 404.php     контент страниц
  functions.php         e(), data(), partial(), drawing(), vite_bundle()
  partials/             hero, stats, services, projects(+lightbox), process,
                        about, reviews, contact(+форма), footer, section-head
  drawings/             inline-SVG чертежи (plan, section, frame, facade)
  data/                 данные массивы (services, projects, steps, …)
api/                    ContactHandler, MailerService, RateLimiter (не трогаем)
src/
  js/                   main.js + modules/ (header, reveal, counter,
                        lightbox, reviews, form)
  styles/               tailwind.css (токены + слои), main.scss + partials
projects/               SVG-обложки проектов (генерируются npm run gen:images)
assets/                 вывод Vite (в git не попадает)
```

## Форма

- `POST /index.php?action=form` — единый эндпоинт.
- JS (`fetch`, `Accept: application/json`) получает JSON вида
  `{"ok":true}` / `{"ok":false,"error":"…"}`.
- Обычный HTML-post (без JS) — редирект 303 на главную с `?sent=1` или
  `?error=…`, блокировка honeypot-поля `website`.

## Заметки

- Шрифты — Geist / Geist Mono с Google Fonts (кириллица включена).
- `html.js` на `<html>` из JS-скрипта в `<head>`: без JS reveal/чертежи
  остаются видимыми, меню раскрывается через `<noscript>`.
- Swiper-импорты подключены в `@layer components` (`tailwind.css`), чтобы
  утилиты Tailwind по-прежнему перебивали стили Swiper.
- Папка `arch/` в корне — пустая копия от старого миграционного шага;
  удалите её из проводника/терминала IDE, если она мешает.
